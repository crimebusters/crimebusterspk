<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 3/1/13
 * Time: 12:47 PM
 * To change this template use File | Settings | File Templates.
 */
include ("../Repository/CategoryRepository.php");

class CategoryController
{
    function __construct()
    {
    }

    public function AddCategory(CategoryModel $categoryModel)
    {
        $categoryRepository = new CategoryRepository();
        $categoryRepository->Add($categoryModel);
    }


    public function GetAll()
    {
        $repository = new CategoryRepository();
        $allRecords = $repository->GetAll();

        $categories = new ArrayObject();
        print_r($allRecords);
        foreach($allRecords as $categoryKey=>$categoryValue)
        {

        }
    }

}




?>
