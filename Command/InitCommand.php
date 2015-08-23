<?php

namespace AlexDpy\SimpleAclBundle\Command;

use Doctrine\DBAL\Schema\SchemaException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('alex-dpy:simple-acl:init')
            ->setDescription('Mounts ACL tables in the database')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command mounts ACL tables in the database.
  <info>php %command.full_name%</info>
The name of the DBAL connection must be configured in your <info>app/config/security.yml</info> configuration file in the <info>alex_dpy_simple_acl.connection</info> variable.
  <info>security:
      acl:
          connection: default</info>
EOF
            )
        ;
    }
    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = $this->getContainer()->get('alex_dpy_simple_acl.connection');
        $schema = $this->getContainer()->get('alex_dpy_simple_acl.schema');

        try {
            $schema->addToSchema($connection->getSchemaManager()->createSchema());
        } catch (SchemaException $e) {
            $output->writeln('Aborting: ' . $e->getMessage());

            return 1;
        }

        foreach ($schema->toSql($connection->getDatabasePlatform()) as $sql) {
            $connection->exec($sql);
        }

        $output->writeln('ACL tables have been initialized successfully.');

        return 0;
    }
}
