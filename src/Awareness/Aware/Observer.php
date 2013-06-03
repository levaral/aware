<?php namespace Awareness\Aware;

class Observer {

  /**
   * Validates the model before saving
   *
   * @param  Awareness\Aware
   * @return bool
   */
  public function saving(Model $model)
  {
    if ($model->isForced()) {
      $model->isValid();
      return true;
    } else {
      return $model->isValid();
    }
  }

  /**
   * Validates the model before saving
   *
   * @param  Awareness\Aware
   * @return bool
   */
  public function saved($model)
  {
    $model->clearForce()->clearOverrides();
  }

}