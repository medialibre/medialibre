// Open simple dialogs on top of an editor. Relies on dialog.css.

(function() {
  function dialogDiv(cm, template) {
    var wrap = cm.getWrapperElement();
    if(hasClass(wrap.firstChild, 'quicktags-toolbar')) {
      var dialog = insertAfter(document.createElement("div"), wrap.firstChild);
    }
    else {
      var dialog = wrap.insertBefore(document.createElement("div"), wrap.firstChild);
    }
    dialog.className = "CodeMirror-dialog";
    dialog.innerHTML = '<div>' + template + '</div>';
    return dialog;
  }
  function hasClass(ele,cls) {
    return ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
  }
  function insertAfter(newElement,targetElement) {
    var parent = targetElement.parentNode;
    if(parent.lastchild == targetElement) {
      return parent.appendChild(newElement);
    }
    else {
      return parent.insertBefore(newElement, targetElement.nextSibling);
    }
  }
  
  CodeMirror.defineExtension("openDialog", function(template, callback) {
    var dialog = dialogDiv(this, template);
    var closed = false, me = this;
    function close() {
      if (closed) return;
      closed = true;
      dialog.parentNode.removeChild(dialog);
    }
    var inp = dialog.getElementsByTagName("input")[0];
    if (inp) {
      CodeMirror.connect(inp, "keydown", function(e) {
        if (e.keyCode == 13 || e.keyCode == 27) {
          CodeMirror.e_stop(e);
          close();
          me.focus();
          if (e.keyCode == 13) callback(inp.value);
        }
      });
      inp.focus();
      CodeMirror.connect(inp, "blur", close);
    }
    return close;
  });

  CodeMirror.defineExtension("openConfirm", function(template, callbacks) {
    var dialog = dialogDiv(this, template);
    var buttons = dialog.getElementsByTagName("button");
    var closed = false, me = this, blurring = 1;
    function close() {
      if (closed) return;
      closed = true;
      dialog.parentNode.removeChild(dialog);
      me.focus();
    }
    buttons[0].focus();
    for (var i = 0; i < buttons.length; ++i) {
      var b = buttons[i];
      (function(callback) {
        CodeMirror.connect(b, "click", function(e) {
          CodeMirror.e_preventDefault(e);
          close();
          if (callback) callback(me);
        });
      })(callbacks[i]);
      CodeMirror.connect(b, "blur", function() {
        --blurring;
        setTimeout(function() { if (blurring <= 0) close(); }, 200);
      });
      CodeMirror.connect(b, "focus", function() { ++blurring; });
    }
  });
})();