/**
 * Theme-Handler Modul
 * Verwaltet die Light/Dark Mode Funktionalität
 */

export class ThemeHandler {
  constructor() {
    this.themeToggle = document.getElementById('theme-toggle');
    this.themeToggleIcon = document.getElementById('theme-toggle-icon');
    
    this.init();
  }
  
  init() {
    // Theme beim Start setzen
    this.setTheme(this.getPreferredTheme());
    
    // Event-Listener für Theme-Toggle
    if (this.themeToggle) {
      this.themeToggle.addEventListener('click', () => {
        this.toggleDarkMode();
      });
    }
  }
  
  setTheme(mode) {
    if (mode === 'dark') {
      document.body.classList.add('dark-mode');
      if (this.themeToggleIcon) {
        this.themeToggleIcon.classList.remove('bi-sun-fill');
        this.themeToggleIcon.classList.add('bi-moon-stars-fill');
      }
    } else {
      document.body.classList.remove('dark-mode');
      if (this.themeToggleIcon) {
        this.themeToggleIcon.classList.remove('bi-moon-stars-fill');
        this.themeToggleIcon.classList.add('bi-sun-fill');
      }
    }
    
    // Emoji Picker Theme anpassen
    document.querySelectorAll('.emoji-picker').forEach(picker => {
      if (mode === 'dark') {
        picker.classList.add('emoji-picker-dark');
        picker.classList.remove('emoji-picker-light');
      } else {
        picker.classList.add('emoji-picker-light');
        picker.classList.remove('emoji-picker-dark');
      }
    });
    
    // Emoji-Search und Emoji-Category-Tab Hintergrund anpassen
    document.querySelectorAll('.emoji-search').forEach(el => {
      if (mode === 'dark') {
        el.classList.add('bg-dark');
        el.classList.remove('bg-light');
      } else {
        el.classList.add('bg-light');
        el.classList.remove('bg-dark');
      }
    });
    
    document.querySelectorAll('.emoji-category-tab').forEach(el => {
      if (mode === 'dark') {
        el.classList.add('bg-dark');
        el.classList.remove('bg-light');
      } else {
        el.classList.add('bg-light');
        el.classList.remove('bg-dark');
      }
    });
    
    // Event auslösen, damit andere Komponenten auf Themeänderungen reagieren können
    const themeEvent = new CustomEvent('themeChanged', {
      detail: {
        theme: mode
      }
    });
    document.dispatchEvent(themeEvent);
  }
  
  getPreferredTheme() {
    return localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  }
  
  toggleDarkMode() {
    const current = document.body.classList.contains('dark-mode') ? 'dark' : 'light';
    const next = current === 'dark' ? 'light' : 'dark';
    this.setTheme(next);
    localStorage.setItem('theme', next);
  }
}