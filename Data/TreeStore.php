<?php

namespace NewbridgeGreen\ExtJSBundle\Data;

use JMS\SerializerBundle\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 */
class TreeStore
{

    static private $_ancestors = 'ancestors';
    static private $_parent = 'parent';
    static private $_children = 'children';
    static private $_text = 'roleTitle';

    /** @JMS\Expose */
    private $_root = array();

    private $_includeRoot = true;

    protected $_idProperty = 'id';
    protected $_childrenProperty = 'children';
    protected $_rootTextProperty = 'text';

    /**
     * Constructor
     *
     * @param  string|null $identifier
     * @param  array|Traversable|null $nodes
     * @param  string|null $label
     * @return void
     */
    public function __construct($nodes = null, $root = null)
    {
        if ($root === null) {
            $defaultRoot = new Node();
            $defaultRoot->setProperty('root', true);
            $this->setRoot($defaultRoot);
        } elseif ($root === false) {
            $defaultRoot = new Node();
            $defaultRoot->setProperty('root', true);
            $this->setRoot($defaultRoot);
            $this->_includeRoot = false;
        }
        if (null !== $nodes && !empty($nodes)) {
            $this->setNodes($nodes);
        }
    }

    public function getRootTexProperty()
    {
        return $this->_rootTextProperty;
    }

    public function setRootTextProperty($rootTextProperty)
    {
        $this->_rootTextProperty = $rootTextProperty;
        return $this;
    }

    public function getRootText()
    {
        return $this->_rootText;
    }

    public function setRootText($rootText)
    {
        $this->_root->setProperty($this->_rootTextProperty, $rootText);
        return $this;
    }

    public function getRoot()
    {
        return $this->_root;
    }

    public function setRoot(Node $rootNode)
    {
        $this->_root = $rootNode;
        return $this;
    }

    public function setIncludeRoot($includeRoot)
    {

        $this->_includeRoot($includeRoot);
        return $this;
    }

    /**
     * Set the nodes to collect
     *
     * @param array|Traversable $nodes
     * @return Zend_Dojo_Data
     */
    public function setNodes($nodes)
    {
        $this->clearNodes();
        //usort($nodes, array('self', '_compareAncestors'));
        return $this->addNodes($nodes);
    }

    /**
     * Add an individual node, optionally by identifier
     *
     * @param  array|object $node
     * @return Zend_ExtJS_Store
     */
    public function addNode($node)
    {

        if (!is_object($node) && !is_array($node)) {
            throw new \Exception('Only arrays and objects may be attached');
        }

        if (!$node instanceof Node) {
            $node = new Node($node);
        }

        if (!$node->hasParent()) {
             $this->_root->appendChild($node);
        } else {
            $parent = $this->findNode($node->getParentId());
            if ($parent) {
                $parent->appendChild($node);
            } else {
                $this->_root->appendChild($node);
            }
        }
    }

    /**
     * Add multiple nodes at once
     *
     * @param  array|Traversable $nodes
     * @return Zend_ExtJS_Store
     */
    public function addNodes($nodes)
    {
        if (
            !is_array($nodes) &&
            (!is_object($nodes) || !($nodes instanceof Traversable))
        ) {
            throw new \Exception(
                'Only arrays and Traversable objects may be added to ' .
                __CLASS__
            );
        }

        foreach ($nodes as $node) {
            $this->addNode($node);
        }

        return $this;
    }

    public function findNode($nodeId)
    {
        return $this->searchNodes($this->_root->getChildren(), $nodeId);
    }

    public function searchNodes($nodes, $nodeId)
    {
        $found = false;
        foreach ($nodes as $node) {
            if ($found === false) {
                if ($node->getProperty($this->_idProperty) === $nodeId) {
                    $found = $node;
                } elseif ($node->hasChildren()) {
                    $found = $this->searchNodes($node->getChildren(), $nodeId);
                }
            }
        }
        return $found;
    }

    /**
     * Get all nodes as an array
     *
     * Serializes nodes to arrays.
     *
     * @return array
     */
    public function getTree()
    {
        return $this->_root;
    }

    /**
     * Remove all nodes at once
     *
     * @return Zend_ExtJS_Store
     */
    public function clearNodes()
    {
        $this->_tree = array();
        $this->_tree[self::$_children] = array();
        return $this;
    }

    /**
     * Seralize entire data structure, including identifier and label, to array
     *
     * @return array
     */
    public function toArray()
    {
        if ($this->_includeRoot === false) {
            $array = array();
            foreach ($this->getRoot()->getChildren() as $child) {
                $array[] = $child->toArray();
            }
            return $array;
        }
        return $this->getRoot()->toArray();
    }

    /**
     * Serialize to JSON (dojo.data format)
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Serialize to string (proxy to {@link toJson()})
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    private static function _compareAncestors($a, $b)
    {
        if (count($a[self::$_ancestors]) == count($b[self::$_ancestors])) {
            return 0;
        }

        return (count($a[self::$_ancestors]) < count($b[self::$_ancestors])) ? -1 : 1;
    }

}

