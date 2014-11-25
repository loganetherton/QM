<?php
/**
 * Abstract workflow class.
 * This should be a base class for all workflows.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
abstract class Workflow
{
    /**
     * @var string $name Workflow name.
     */
    protected $name;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->didConstruct();
    }

    /**
     * Internal class constructor.
     */
    protected function didConstruct()
    {
        return null;
    }

    /**
     * Set workflow name.
     * @param string $name Workflow name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string Workflow name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Execute workflow.
     * @param array $args Workflow arguments
     */
    abstract public function execute($args = array());
}