import { Controller } from '@hotwired/stimulus';

/**
 * Contrôleur Stimulus pour appliquer des images d'arrière-plan aux cartes de cours
 * @class CourseBackgroundController
 */
export default class extends Controller {
  connect() {
    console.log('CourseBackgroundController connected');
    this.applyBackground();
  }

  applyBackground() {
    // Récupérer l'URL de l'image d'arrière-plan depuis l'attribut data
    const backgroundUrl = this.element.dataset.background;
    
    if (backgroundUrl) {
      // Créer un pseudo-élément ::before avec l'image d'arrière-plan
      this.element.classList.add('bg-loaded');
      
      // Appliquer l'image d'arrière-plan directement sur le pseudo-élément via style
      const styleSheet = document.styleSheets[0];
      const selector = `#${this.element.id}.bg-loaded::before`;
      const rule = `{
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url('${backgroundUrl}');
        background-size: cover;
        background-position: center;
        opacity: 0.15;
        z-index: 0;
      }`;
      
      try {
        styleSheet.insertRule(selector + rule, styleSheet.cssRules.length);
      } catch (e) {
        console.error('Error applying background:', e);
      }
    }
  }
}