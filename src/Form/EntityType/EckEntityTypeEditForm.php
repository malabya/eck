<?php

/**
 * @file
 * Contains \Drupal\eck\Form\EntityType\EckEntityTypeEditForm.
 */

namespace Drupal\eck\Form\EntityType;

use Drupal\Core\Form\FormStateInterface;

/**
 * Provides the edit form for ECK Entity Type.
 *
 * @ingroup eck
 */
class EckEntityTypeEditForm extends EckEntityTypeFormBase {

  /**
   * {@inheritdoc}
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    $actions = parent::actions($form, $form_state);
    // Change the submit button value.
    $actions['submit']['#value'] = $this->t('Update @type', array('@type' => $this->entity->label()));

    return $actions;
  }

}
