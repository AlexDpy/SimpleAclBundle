<?php

namespace AlexDpy\SimpleAclBundle\EventListener;

use AlexDpy\Acl\Schema\AclSchema;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

class AclSchemaListener
{
    /**
     * @var AclSchema
     */
    private $aclSchema;

    /**
     * @param AclSchema $aclSchema
     */
    public function __construct(AclSchema $aclSchema)
    {
        $this->aclSchema = $aclSchema;
    }

    /**
     * @param GenerateSchemaEventArgs $args
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $args)
    {
        $this->aclSchema->addToSchema($args->getSchema());
    }
}
