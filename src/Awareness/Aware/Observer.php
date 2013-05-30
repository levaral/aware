<?php namespace Awareness\Aware;

class Observer {

  /**
   * Validates the model before saving
   *
   * @param  Awareness\Aware
   * @return bool
   */
  public function saving(Awareness\Aware $model)
  {
    if (!$model->saveForced()) {
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
    $model->clearForce()
      ->overrideMessages()
      ->overrideRules();
  }

}