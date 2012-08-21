<?php

namespace NewbridgeGreen\ExtJSBundle\Data;

use JMS\SerializerBundle\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 */
class Node
{

    protected $_nodeProperties = array(
        'id' => null, // String
        'parentId' => null, // String
        'index' => null, // Int
        'depth' => 0, // Int
        'expanded' => true, // Bool
        'expandable' => true, // Bool
        'checked' => null,
        'leaf' => false, // Bool
        'cls' => null, // String
        'iconCls' => null, // String
        'icon' => null, // String
        'root' => false, // Bool
        'isLast' => false, // Bool
        'isFirst' => false, // Bool
        'allowDrop' => true, // Bool
        'allowDrag' => true, // Bool
        'loaded' => false, // Bool
        'loading' => false, // Bool
        'href' => null, // String
        'hrefTarget' => null, // String
        'qtip' => null, // String
        'qtitle' => null // String
    );

    /** @JMS\Expose */
    protected $_node = null;

    protected $_firstChild;
    protected $_lastChild;
    protected $_parentNode;
    protected $_previousSibling;
    protected $_nextSibling;
    protected $_childNodes = array();

    protected $_idProperty = 'id';
    protected $_parentProperty = 'parent';
    protected $_childrenProperty = 'children';

    protected $_addEmptyChildren = true;

    /**
     * Constructor
     *
     * @param  string|null $identifier
     * @param  array|Traversable|null $nodes
     * @param  string|null $label
     * @return void
     */
    public function __construct($node = null)
    {
        $this->_node = new \StdClass;

        if (null !== $node) {
            $this->setNode($node);
        }
    }

    /**
     * Set an individual node, optionally by identifier (overwrites)
     *
     * @param  array|object $node
     * @param  string|null $identifier
     * @return Zend_Dojo_Data
     */
    public function setNode($node)
    {
        // Add in the ExtJS Tree properties it accepts for a node
        $this->_node = (object) array_merge((array) $node, (array) $this->_nodeProperties);
        //$this->_node = $node + $this->_nodeProperties;
        var_dump($this->_node);
        return $this;
    }

    public function removeParent()
    {
        $this->_parentNode = null;
        return $this;
    }

    public function hasParent()
    {
        return ($this->getProperty('parentId') !== null);// ? true : false;
    }

    public function getFirstChild()
    {
        return reset($this->_childNodes[0]);
    }

    public function getLastChild()
    {
        return end($this->_childNodes[]);
    }

    public function getChildAt($index)
    {
        return $this->_childNodes[$index];
    }

    public function getChildren()
    {
        return $this->_childNodes;
    }

    public function hasChildren()
    {
        return !empty($this->_childNodes);
    }

    public function appendChild(Node $node)
    {
        if ($this->isRoot() === false) {
            $node->setParent($this->getId());
        }
        $this->_childNodes[] = $node;
    }

    /**
     * Does an node with the given identifier exist?
     *
     * @param  string|int $node
     * @return bool
     */
    public function isNode($node)
    {
        return $node instanceof $this;
    }

    public function isRoot()
    {
        return $this->getProperty('root');
    }
    /**
     * Retrieve an node by identifier
     *
     * Node retrieved will be flattened to an array.
     *
     * @param  string $id
     * @return array
     */
    public function getNode()
    {
        return $this->_node;
    }

    /**
     * Remove all nodes at once
     *
     * @return Zend_ExtJS_Store
     */
    public function clearNode()
    {
        $this->_node = null;
        return $this;
    }

    public function getId()
    {
        return $this->getProperty('id');
    }

    public function getParentId()
    {
        return $this->getProperty($this->_parentProperty);
    }

    public function getProperty($property)
    {
        return $this->_node->{$property};
    }

    public function setProperty($property, $value)
    {
        //var_dump($property);
        $this->_node->{$property} = $value;
        return $this;
    }

    public function setChildrenProperty($value)
    {
        $this->_childrenProperty = $value;
        return $this;
    }

    /**
     * Seralize entire data structure, including identifier and label, to array
     *
     * @return array
     */
    public function toArray()
    {


        foreach ($this->_childNodes as $child) {
            $this->_node->{$this->_childrenProperty}[] = $child->toArray();
        }

        if ($this->_addEmptyChildren && empty($this->_childNodes)) {
            $this->_node{$this->_childrenProperty} = array();
        }

        return get_object_vars($this->_node);
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

    public function __set($property, $value)
    {
        $this->_node->{$property} = $value;
    }

}