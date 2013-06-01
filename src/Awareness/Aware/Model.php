<?php namespace Awareness\Aware;

use Illuminate\Database\Eloquent;
use Illuminate\Support\Contracts\MessageProviderInterface;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Validator;

/**
 * Aware Models
 *    Self-validating Eloquent Models
 */
abstract class Aware extends Eloquent\Model implements MessageProviderInterface
{

    /**
     * Error message container
     *
     * @var Illuminate\Support\MessageBag
     */
    protected
      $errorBag,
      $messages,
      $rules;

    /**
     * Aware Validation Messages
     *
     * @var array $messages
     */
    public static $messages = array();

    /**
     * Aware Validation Rules
     *
     * @var array $rules
     */
    public static $rules = array();

    /**
     * Setup validation events
     */
    public static function boot()
    {
      parent::boot();
      static::observe(new Observer());
    }

    /**
     * Returns the errors container
     *
     * @return Illuminate\Support\MessageBag
     */
    public function getErrors()
    {
        if (!$this->errorBag) {
            $this->errorBag = new MessageBag();
        }
        return $this->errorBag;
    }

    /**
     * Returns the errors container
     *
     * @return Illuminate\Support\MessageBag
     */
    public function getMessageBag()
    {
        return $this->errors();
    }

    /**
     * Get an array of messages for the next save
     *
     * @return array
     */
    public function getMessages()
    {
      return array_merge(static::$messages, $this->messages);
    }

    /**
     * Get an array of rules for the next save
     *
     * @return array
     */
    public function getRules($data=null)
    {
      if ($data) {
        return array_intersect_key(
          array_merge(static::$rules, $this->rules),
          $data
        );
      } else {
        return array_merge(static::$rules, $this->rules);
      }
    }

    /**
     * Validate the Model
     *    runs the validator and binds any errors to the model
     *
     * @param array $rules
     * @param array $messages
     * @return bool
     */
    public function isValid()
    {
        $valid = true;
        $data = $this->getDirty();
        $rules = $this->exists ?
          $this->getRules($data) :
          $this->getRules();

        if ($rules) {
            $validator = Validator::make(
              $data,
              $rules,
              $this->getMessages()
            );
            $valid = $validator->passes();
        }

        if (!$valid) {
            $this->errorBag = $validator->errors();
        } elseif ($this->errorBag && $this->errorBag->any()) {
            $this->errorBag = new MessageBag();
        }

        return $valid;
    }

    /**
     * Clears the force-save flag
     *
     * @return Awareness\Aware
     */
    public function clearForce()
    {
      $this->forceSave = false;
      return $this;
    }

    /**
     * Clears message & rule overrides
     *
     * @return Awareness\Aware
     */
    public function clearOverrides()
    {
      $this->messages = null;
      $this->rules = null;
      return $this;
    }

    /**
     * Perform next save without validating
     *
     * @return Awareness\Aware
     */
    public function force()
    {
      $this->forceSave = true;
      return $this;
    }

    /**
     * Returns true if forced flag is set
     *
     * @return bool
     */
    public function isForced()
    {
      return $this->forceSave;
    }

    /**
     * Set message overrides for the next save
     *
     * @return Awareness\Aware
     */
    public function overrideMessages($messages)
    {
      $this->messages = $messages;
      return $this;
    }

    /**
     * Set rule overrides for the next save
     *
     * @return Awareness\Aware
     */
    public function overrideRules($rules)
    {
      $this->rulesOverrides = $rules;
      return $this;
    }

}
