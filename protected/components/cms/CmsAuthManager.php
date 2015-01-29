<?php

class CmsAuthManager extends CDbAuthManager {
    /**
     * Performs access check for the specified user.
     * @param string $itemName the name of the operation that need access check
     * @param mixed $userId the user ID. This should can be either an integer and a string representing
     * the unique identifier of a user. See {@link IWebUser::getId}.
     * @param array $params name-value pairs that would be passed to biz rules associated
     * with the tasks and roles assigned to the user.
     * Since version 1.1.11 a param with name 'userId' is added to this array, which holds the value of <code>$userId</code>.
     * @return boolean whether the operations can be performed by the user.
     */
    public function checkAccess($itemName,$userId,$params=array())
    {

        if (isset(Yii::app()->user->role)&&Yii::app()->user->role=='admin')
            return true;
        $assignments=$this->getAuthAssignments($userId);
        return $this->checkAccessRecursive($itemName,$userId,$params,$assignments);
    }
}