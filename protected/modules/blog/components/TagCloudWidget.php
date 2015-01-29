<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 23.01.13
 * Time: 16:26
 * To change this template use File | Settings | File Templates.
 */


class TagCloudWidget  extends CWidget
{
    public function init()
    {
        $tags = PostTag::model()->findAll();
        $sumFrequency = 0;
        $minFrequency = null;
        $maxFrequency = 0;
        foreach ($tags as $tag) {
            if ($minFrequency > $tag->frequency || $minFrequency==null)
                $minFrequency = $tag->frequency;
            if ($maxFrequency < $tag->frequency)
                $maxFrequency = $tag->frequency;
            $sumFrequency += $tag->frequency;
        }
        $tagsNameSize = array();
        foreach ($tags as $tag){
            if ($minFrequency === $maxFrequency) {
                $size = 8;
            } else {
                $size = round(($tag->frequency - $minFrequency)*8/($maxFrequency - $minFrequency));
            }
            $tagsNameSize[] = array('name'=>$tag->name, 'size'=>$size);
        }
        $this->render('tagCloudWidget',array('tagsNameSize'=>$tagsNameSize));
    }
}