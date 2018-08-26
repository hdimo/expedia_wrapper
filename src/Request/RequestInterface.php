<?php
/**
 * Created by PhpStorm.
 * User: khaled
 * Date: 8/1/18
 * Time: 4:26 PM
 */

namespace Jkhaled\Expedia\Request;


interface RequestInterface
{
    /**
     * get method name to be in link based on request type
     *
     * @return mixed
     */
    public function getMethod() : string ;

    /**
     * prepare xml request to be sent
     * @return mixed
     */
    public function prepareRequest();
}