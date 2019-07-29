<?php


namespace Mouf\Database\TDBM\Utils;

use TheCodingMachine\TDBM\ConfigurationInterface;
use Mouf\Database\TDBM\MoufConfiguration;
use TheCodingMachine\TDBM\TDBMService;
use Mouf\MoufManager;
use TheCodingMachine\TDBM\Utils\GeneratorListenerInterface;
use TheCodingMachine\TDBM\Utils\TDBMDaoGenerator;
use function var_export;

class MoufDiListener implements GeneratorListenerInterface
{

    /**
     * @param ConfigurationInterface $configuration
     * @param BeanDescriptorInterface[] $beanDescriptors
     */
    public function onGenerate(ConfigurationInterface $configuration, array $beanDescriptors): void
    {
        // Let's generate the needed instance in Mouf.
        $moufManager = MoufManager::getMoufManager();

        $daoFactoryInstanceName = null;
        if ($configuration instanceof MoufConfiguration) {
            $daoFactoryInstanceName = $configuration->getDaoFactoryInstanceName();
            $daoFactoryClassName = $configuration->getDaoNamespace().'\\Generated\\'.$configuration->getNamingStrategy()->getDaoFactoryClassName();
            $moufManager->declareComponent($daoFactoryInstanceName, $daoFactoryClassName, false, MoufManager::DECLARE_ON_EXIST_KEEP_INCOMING_LINKS);
        }

        $tdbmServiceInstanceName = $this->getTdbmInstanceName($configuration);

        foreach ($beanDescriptors as $beanDescriptor) {
            $daoName = $beanDescriptor->getDaoClassName();

            // Rename all DAOs to full-class name (migration from 5.0 to 5.1+ naming schema)
            $instanceName = TDBMDaoGenerator::toVariableName($daoName);
            if ($moufManager->instanceExists($instanceName) && !$moufManager->instanceExists($configuration->getDaoNamespace().'\\'.$daoName)) {
                $moufManager->renameComponent($instanceName, $configuration->getDaoNamespace().'\\'.$daoName);

                // Let's create a "Link" between old Mouf::getXXXDao and new Mouf::getNamespaceXXXDao
                $moufManager->createInstanceByCode()->setName($instanceName)->setCode('return $container->get('.var_export($configuration->getDaoNamespace().'\\'.$daoName, true).');');

                // Let's unset the setter. It is useless now.
                if ($daoFactoryInstanceName !== null) {
                    $moufManager->unsetParameterForSetter($daoFactoryInstanceName, 'set'.$daoName);
                }
            }

            $instanceName = $configuration->getDaoNamespace().'\\'.$daoName;
            if (!$moufManager->instanceExists($instanceName)) {
                $moufManager->declareComponent($instanceName, $configuration->getDaoNamespace().'\\'.$daoName);
            }
            $moufManager->setParameterViaConstructor($instanceName, 0, $tdbmServiceInstanceName, 'object');
        }

        if ($daoFactoryInstanceName !== null) {
            $moufManager->setParameterViaConstructor($daoFactoryInstanceName, 0, 'return $container;', 'primitive', 'php');
        }

        $moufManager->rewriteMouf();
    }

    private function getTdbmInstanceName(ConfigurationInterface $configuration) : string
    {
        $moufManager = MoufManager::getMoufManager();

        $configurationInstanceName = $moufManager->findInstanceName($configuration);
        if (!$configurationInstanceName) {
            throw new \TDBMException('Could not find TDBM instance for configuration object.');
        }

        // Let's find the configuration
        $tdbmServicesNames = $moufManager->findInstances(TDBMService::class);

        foreach ($tdbmServicesNames as $name) {
            if ($moufManager->getInstanceDescriptor($name)->getConstructorArgumentProperty('configuration')->getValue()->getName() === $configurationInstanceName) {
                return $name;
            }
        }

        throw new \TDBMException('Could not find TDBMService instance.');
    }
}
