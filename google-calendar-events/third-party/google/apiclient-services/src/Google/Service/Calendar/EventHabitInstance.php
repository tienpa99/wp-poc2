<?php

namespace SimpleCalendar\plugin_deps;

/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */
class Google_Service_Calendar_EventHabitInstance extends Google_Model
{
    protected $dataType = 'SimpleCalendar\plugin_deps\Google_Service_Calendar_HabitInstanceData';
    protected $dataDataType = '';
    public $parentId;
    /**
     * @param Google_Service_Calendar_HabitInstanceData
     */
    public function setData(Google_Service_Calendar_HabitInstanceData $data)
    {
        $this->data = $data;
    }
    /**
     * @return Google_Service_Calendar_HabitInstanceData
     */
    public function getData()
    {
        return $this->data;
    }
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }
    public function getParentId()
    {
        return $this->parentId;
    }
}
