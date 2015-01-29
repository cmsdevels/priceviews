<?php

class CmsAccessControlFilter extends CFilter
{
    /**
     * @var string the error message to be displayed when authorization fails.
     * This property can be overridden by individual access rule via {@link CAccessRule::message}.
     * If this property is not set, a default error message will be displayed.
     * @since 1.1.1
     */
    public $message;

    public function isAllowed($user, $filterChain)
    {
        $specs = $filterChain->controller->accessSpecs();
        if (empty($specs))
            return true;
        if ($user->checkAccess('admin'))
            return true;
        else {
            if (isset($specs['operations'])&&isset($specs['operations'][$filterChain->action->id])) {
                if (is_array($specs['operations'][$filterChain->action->id])) {
                    if (isset($specs['operations'][$filterChain->action->id]['access'])) {
                        if ($specs['operations'][$filterChain->action->id]['access']=='allow')
                            return true;
                        elseif ($specs['operations'][$filterChain->action->id]['access']=='isAdmin'&&$user->isAdmin)
                            return true;
                        elseif ($specs['operations'][$filterChain->action->id]['access']=='@'&&!$user->isGuest)
                            return true;
                    } else
                        return false;
                } else {
                    if (isset($filterChain->controller->module))
                        return $user->checkAccess($filterChain->controller->module->id.'/'.$filterChain->controller->id.'.'.$filterChain->action->id);
                    else
                        return $user->checkAccess($filterChain->controller->id.'.'.$filterChain->action->id);
                }
            }
            return false;
        }
    }

    /**
     * Performs the pre-action filtering.
     * @param CFilterChain $filterChain the filter chain that the filter is on.
     * @return boolean whether the filtering process should continue and the action
     * should be executed.
     */
    protected function preFilter($filterChain)
    {
        $app=Yii::app();
        $user=$app->getUser();
        if ($this->isAllowed($user, $filterChain)==false) {
            $this->accessDenied($user,$this->resolveErrorMessage());
            return false;
        }
        return true;
    }

    protected function resolveErrorMessage()
    {
        return Yii::t('yii','You are not authorized to perform this action.');
    }

    /**
     * Denies the access of the user.
     * This method is invoked when access check fails.
     * @param IWebUser $user the current user
     * @param string $message the error message to be displayed
     */
    protected function accessDenied($user,$message)
    {
        if($user->getIsGuest())
            $user->loginRequired();
        else
            throw new CHttpException(403,$message);
    }
}