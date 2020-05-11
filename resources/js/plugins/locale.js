const hl = window.document.head.querySelector('meta[name="lang"]');
export default hl ? hl.content : 'en'
