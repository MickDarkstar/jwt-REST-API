<?php
final class IndexController extends BaseController
{
    public static function Home()
    {
        Response::OK(null, "This is home")
    }
}
