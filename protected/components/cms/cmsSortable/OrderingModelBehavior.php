<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 09.09.13
 * Time: 14:50
 */

class OrderingModelBehavior extends CActiveRecordBehavior
{
    public $attribute = 'ordering';

    public $groupAttribute = null;

    public function beforeSave($event)
    {
        $owner = $this->owner;
        if ($owner->isNewRecord) {
            $count = $owner->count($this->groupAttribute !== null ? $this->groupAttribute.'='.$this->owner->{$this->groupAttribute} : '');
            $owner->{$this->attribute} = $count + 1;
        } elseif ($this->groupAttribute !== null && $this->owner->{$this->groupAttribute} != $this->_oldGroupAttributeId) {
            $oldOrdering = $this->owner->{$this->attribute};
            CActiveRecord::model(get_class($owner))->updateCounters(
                array($this->attribute=>-1),
                $this->attribute.' > '.$oldOrdering.' AND '.$this->groupAttribute.'='.$this->_oldGroupAttributeId
            );
            $owner->{$this->attribute} =  $owner->count($this->groupAttribute.'='.$this->owner->{$this->groupAttribute}) + 1;
        }
        $event->isValid = true;
    }

    public function afterDelete($event)
    {
        $current_ordering = $this->owner->{$this->attribute};
        CActiveRecord::model(get_class($this->owner))->updateCounters(
            array($this->attribute=>-1),
            $this->attribute.' > '.$current_ordering.(
            $this->groupAttribute !== null ? ' AND '.$this->groupAttribute.'='.$this->owner->{$this->groupAttribute} : ""
            )
        );
    }


    public function attach($owner) {
        parent::attach($owner);
        $validators = $this->owner->getValidatorList();
        $validator = CValidator::createValidator('safe', $this->owner, $this->attribute);
        $validators->add($validator);
    }

    protected $_oldGroupAttributeId = null;

    public function afterFind($event) {
        parent::afterFind($event);
        if ($this->groupAttribute !== null)
            $this->_oldGroupAttributeId = $this->owner->{$this->groupAttribute};
    }

} 