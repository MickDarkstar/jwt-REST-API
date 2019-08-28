<?php
class IndexController extends BaseController
{
    public static function Home()
    {
        echo json_encode(("this is home"));
    }
}
