<?php

/**
 * @file
 * Contains \Drupal\eck\Form\Entity\EckEntityForm.
 */

namespace Drupal\eck\Form\Entity;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\Language;

/**
 * Form controller for the ECK entity forms.
 *
 * @ingroup eck
 */
class EckEntityForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    $form['langcode'] = array(
      '#title' => $this->t('Language'),
      '#type' => 'language_select',
      '#default_value' => $entity->getUntranslated()->language()->getId(),
      '#languages' => Language::STATE_ALL,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $this->entity->save();
    $form_state->setRedirect('entity.' . $this->entity->getEntityTypeId() . '.canonical', array($this->entity->getEntityTypeId() => $this->entity->id()));
    $entity = $this->getEntity();
    $entity->save();
  }

}
