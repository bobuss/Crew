<?php

class projectDeleteAction extends sfAction
{
  /**
   * @throws Exception
   * @param $request
   * @return void
   */
  public function execute($request)
  {
    $id = $request->getParameter('id');

    // get the propel connection
    $con = Propel::getConnection();
    $con->beginTransaction();

    $name = 'N/A';
    $value = 'N/A';
    try
    {
      $repository = RepositoryQuery::create()
        ->filterById($id)
        ->findOne($con);
      if($repository){
        $name = $repository->getName();
        $value = $repository->getValue();
        $repository->delete($con);
      }
      $con->commit();
      $this->getUser()->setFlash('notice', sprintf("The project '%s' has been deleted successfully. Remember to delete the directory %s.", $name, $value));
    }
    catch (Exception $e)
    {
      $con->rollBack();
      $this->getUser()->setFlash('error', sprintf("Delete failed : ",$e->getMessage()));
    }

    $this->redirect("default/repositoryList");
  }
}
