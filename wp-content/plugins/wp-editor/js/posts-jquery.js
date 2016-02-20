editor_status = '';
tags = {};
(function($){
  $(window).resize(function() {
    $('.CodeMirror-scroll').height($('.CodeMirror-wrap').height() - $('#wp-editor-quicktags').height() - 3);
  });
  $(window).load(function() {
    if(!isTinyMCE()) {
      setTimeout(setupPostEditor, 50);
    }
    else {
      editor_status = 'tmce';
    }
  })
  function _datetime() {
    var now = new Date(), zeroise;
    
    zeroise = function(number) {
      var str = number.toString();
      if(str.length < 2) {
        str = "0" + str;
      }
      return str;
    }
    
    return now.getUTCFullYear() + '-' + zeroise(now.getUTCMonth() + 1) + '-' + zeroise(now.getUTCDate()) + 'T' + zeroise(now.getUTCHours()) + ':' + zeroise(now.getUTCMinutes()) + ':' + zeroise(now.getUTCSeconds()) + '+00:00';
  }
  function insertOpenCloseTag(tag, title, beginningTag, endTag) {
    
    var selection = editor.getSelection();
    if(selection != '') {
      editor.replaceSelection(beginningTag + selection + endTag, 'end');
      editor.focus();
    }
    else {
      if(!tags[tag]) {
        tags[tag] = {'lastTag': 0};
      }
      if(beginningTag == '') {
        editor.replaceSelection(endTag, 'end');
        $('#wpe_qt_content_' + tag).val(title);
        editor.focus();
      }
      else if(endTag == '') {
        editor.replaceSelection(beginningTag, 'end');
        editor.focus();
      }
      else if(!tags[tag].lastTag || tags[tag].lastTag == 0) {
        editor.replaceSelection(beginningTag, 'end');
        $('#wpe_qt_content_' + tag).val('/' + title);
        editor.focus();
        tags[tag].lastTag = 1;
      }
      else if(tags[tag].lastTag == 1) {
        editor.replaceSelection(endTag, 'end');
        $('#wpe_qt_content_' + tag).val(title);
        editor.focus();
        tags[tag].lastTag = 0;
      }
    }
  }
  function openLinkDialog() {
    if(typeof(wpLink) != 'undefined') {
      wpLink.open();
      return;
    }
  }
  function insertLinkTag(link, title, blank) {
    var selection = editor.getSelection();
    var newLink = link != '' ? link : '';
    var newTitle = title != '' ? '" title="' + title : '';
    var newBlank = blank == true ? '" target="_blank' : ''
    var combinedTags = newLink + newTitle + newBlank;
    if(selection != '') {
      editor.replaceSelection('<a href="' + combinedTags + '">' + selection + '</a>', 'end');
      tags.link = {'lastTag': 0};
    }
    else {
      editor.replaceSelection('<a href="' + combinedTags + '">', 'end');
      $('#wpe_qt_content_link').val('/link');
      tags.link = {'lastTag': 1};
    }
    editor.focus();
  }
  function insertImgTag() {
    var src = prompt(WPEPosts.enterImgUrl, 'http://'), alt;
    if(src) {
      alt = prompt(WPEPosts.enterImgDescription, '');
      editor.replaceSelection('<img src="' + src + '" alt="' + alt + '" />', 'end');
      editor.focus();
    }
  }
  function lookupWord() {
    var word = editor.getSelection();
    if(word == '') {
      word = prompt(WPEPosts.wordLookup, '');
    }
    if(word !== null && /^\w[\w ]*$/.test(word)) {
      window.open('http://www.answers.com/' + encodeURIComponent(word));
    }
  }
  $(document).ready(function(){
    
    $('#ed_toolbar').hide();
    $('#wpe_qt_content_strong').live("click", function() {
      insertOpenCloseTag('strong', 'b', '<strong>', '</strong>');
    })
    $('#wpe_qt_content_em').live("click", function() {
      insertOpenCloseTag('em', 'i', '<em>', '</em>');
    })
    $('#wpe_qt_content_link').live("click", function() {
      if(!tags.link || tags.link.lastTag == 0) {
        openLinkDialog();
      }
      else {
        insertOpenCloseTag('link', 'link', '', '</a>');
        tags.link = {'lastTag': 0};
      }
    })
    $('#wpe_qt_content_block').live("click", function() {
      insertOpenCloseTag('block', 'b-quote', '\n\n<blockquote>', '</blockquote>\n\n');
    })
    $('#wpe_qt_content_del').live("click", function() {
      insertOpenCloseTag('del', 'del', '<del datetime="' + _datetime() + '">', '</del>');
    })
    $('#wpe_qt_content_ins').live("click", function() {
      insertOpenCloseTag('ins', 'ins', '<ins datetime="' + _datetime() + '">', '</ins>');
    })
    $('#wpe_qt_content_img').live("click", function() {
      insertImgTag();
    })
    $('#wpe_qt_content_ul').live("click", function() {
      insertOpenCloseTag('ul', 'ul', '<ul>\n', '</ul>\n\n');
    })
    $('#wpe_qt_content_ol').live("click", function() {
      insertOpenCloseTag('ol', 'ol', '<ol>\n', '</ol>\n\n');
    })
    $('#wpe_qt_content_li').live("click", function() {
      insertOpenCloseTag('li', 'li', '\t<li>', '</li>\n');
    })
    $('#wpe_qt_content_code').live("click", function() {
      insertOpenCloseTag('code', 'code', '<code>', '</code>');
    })
    $('#wpe_qt_content_more').live("click", function() {
      insertOpenCloseTag('more', 'more', '', '<!--more-->');
    })
    $('#wpe_qt_content_page').live("click", function() {
      insertOpenCloseTag('page', 'page', '', '<!--nextpage-->');
    })
    $('#wpe_qt_content_lookup').live("click", function() {
      lookupWord();
    })
    $('#wpe_qt_content_fullscreen').live("click", function() {
      toggleFullscreenEditing();
      editor.focus();
    });
    $('#wpe_qt_content_save').live("click", function() {
      editor.save();
      $('#wp_mce_fullscreen').val($('#content').val());
      wp.editor.fullscreen.save();
      changeReset();
    });
    $('#wp-link-submit').live("click", function() {
      var link = $('#url-field').val();
      var title = $('#link-title-field').val();
      var blank = false;
      if($('#link-target-checkbox').is(':checked')) {
        blank = true;
      }
      insertLinkTag(link, title, blank);
      return false;
    })
    $('#content-tmce').attr('onclick','').unbind('click');
    $('#content-html').attr('onclick','').unbind('click');
    $('#content-tmce').click(function() {
      if(editor_status !== 'tmce') {
        var scrollPosition = editor.getScrollInfo();
        document.cookie="scrollPositionX=" + scrollPosition.x;
        document.cookie="scrollPositionY=" + scrollPosition.y;
        editor.toTextArea();
        id = $(this).attr( 'data-wp-editor-id' );
        switchEditors.go(id, 'tmce');
        editor_status = 'tmce';
      }
    })
    $('#content-html').click(function() {
      if(editor_status !== 'html') {
        id = $(this).attr( 'data-wp-editor-id' );
        switchEditors.go(id, 'html');
        postCodeMirror('content');
        editor.scrollTo(getPositionCookie('scrollPositionX'), getPositionCookie('scrollPositionY'));
        editor_status = 'html';
        return false;
      }
      else {
        var scrollPosition = editor.getScrollInfo();
        editor.toTextArea();
        postCodeMirror('content');
        editor.scrollTo(scrollPosition.x, scrollPosition.y);
        document.cookie="scrollPositionX=" + scrollPosition.x;
        document.cookie="scrollPositionY=" + scrollPosition.y;
        return false;
      }
    })
    $('#post').submit(function(e) {
      changeReset();
      if(editor_status == 'html') {
        var scrollPosition = editor.getScrollInfo();
        document.cookie="scrollPositionX=" + scrollPosition.x;
        document.cookie="scrollPositionY=" + scrollPosition.y;
        editor.save();
      }
    })
  })
  function getPositionCookie(key) {
    currentcookie = document.cookie;
    if(currentcookie.length > 0) {
      firstidx = currentcookie.indexOf(key + "=");
      if(firstidx != -1) {
        firstidx = firstidx + key.length + 1;
        lastidx = currentcookie.indexOf(";",firstidx);
        if(lastidx == -1) {
          lastidx = currentcookie.length;
        }
        return unescape(currentcookie.substring(firstidx, lastidx));
      }
    }
    return "";
  }
  function setupPostEditor() {
    if(!isTinyMCE()) {
      if($('#content').is(':visible')) {
        if(!$('#content_parent').is(':visible')) {
          postCodeMirror('content');
          editor.scrollTo(getPositionCookie('scrollPositionX'), getPositionCookie('scrollPositionY'));
          editor_status = 'html';
        }
      }
    }
  }
  function isTinyMCE() {
    is_tinyMCE_active = true;
    if(typeof(tinyMCE) != "undefined") {
      if (tinyMCE.activeEditor == null || tinyMCE.activeEditor.isHidden() != false) {
        is_tinyMCE_active = false;
      }
    }
    else {
      is_tinyMCE_active = false;
    }
    return is_tinyMCE_active;
  }
  $.fn.setContentCursor = function(start, end) {
    return this.each(function() {
      if(this.setSelectionRange) {
        this.focus();
        this.setSelectionRange(start, end);
      }
      else if(this.createTextRange) {
        var range = this.createTextRange();
        range.collapse(true);
        range.moveEnd('character', end);
        range.moveStart('character', start);
        range.select();
      }
    });
  };
  window.wp_editor_send_to_editor = window.send_to_editor;
  window.send_to_editor = function(html){
    if(editor_status == 'html') {
      var cursor = editor.getCursor(true);
      var lines = $('#content').val().substr(0, this.selectionStart).split("\n");
      var newLength = 0, line = 1, lineArray = [];
      lineArray[0] = 0;
      $.each(lines, function(key, value) {
        newLength = newLength + value.length + 1;
        lineArray[line] = newLength;
        line++;
      });
      editor.toTextArea();
      $('#content').setContentCursor(lineArray[cursor.line] + cursor.ch, lineArray[cursor.line] + cursor.ch);
      window.wp_editor_send_to_editor(html);
      postCodeMirror('content');
      editor.setCursor(cursor.line, cursor.ch + html.length)
      editor.focus();
    }
    else {
      window.wp_editor_send_to_editor(html);
    }
  };
  function postCodeMirror(element) {
    var activeLine = WPEPosts.activeLine;
    editor = CodeMirror.fromTextArea(document.getElementById(element), {
      mode: 'wp_shortcodes',
      theme: WPEPosts.theme,
      lineNumbers: WPEPosts.lineNumbers,
      lineWrapping: WPEPosts.lineWrapping,
      indentWithTabs: WPEPosts.indentWithTabs,
      indentSize: WPEPosts.indentSize,
      tabSize: WPEPosts.tabSize,
      onCursorActivity: function() {
        if(activeLine) {
          editor.setLineClass(hlLine, null, null);
          hlLine = editor.setLineClass(editor.getCursor().line, null, activeLine);
        }
      },
      onChange: function() {
        changeTrue();
      },
      onKeyEvent: function(editor, event) {
        if(typeof(wpWordCount) != 'undefined') {
          editor.save();
          last = 0, co = $('#content');
          $(document).triggerHandler('wpcountwords', [ co.val() ]);
          
          co.keyup(function(e) {
            var k = event.keyCode || event.charCode;
            
            if(k == last) {
              return true;
            }
            if(13 == k || 8 == last || 46 == last) {
              $(document).triggerHandler('wpcountwords', [ co.val() ]);
            }
            last = k;
            return true;
          });
        }
        
      },
      extraKeys: {
        'F11': toggleFullscreenEditing, 
        'Esc': toggleFullscreenEditing
      }
    });
    $('.CodeMirror').css('font-size', WPEPosts.fontSize);
    if(activeLine) {
      var hlLine = editor.setLineClass(0, activeLine);
    }
    if(WPEPosts.editorHeight) {
      $('.CodeMirror-scroll, .CodeMirror, .CodeMirror-gutter').height(WPEPosts.editorHeight + 'px');
      var scrollDivHeight = $('.CodeMirror-scroll div:first-child').height();
      var editorDivHeight = $('.CodeMirror').height();
      if(scrollDivHeight > editorDivHeight) {
        $('.CodeMirror-gutter').height(scrollDivHeight);
      }
    }
    if(!$('.CodeMirror .quicktags-toolbar').length) {
      $('.CodeMirror').prepend('<div id="wp-editor-quicktags" class="quicktags-toolbar">' + 
        '<div id="wp-fullscreen-save">' +
        '<input type="button" id="wpe_qt_content_save" class="button-primary" title="" value="' + WPEPosts.save + '" style="float:right;display:none;font-family: \'Open Sans\', sans-serif;"><span class="spinner"></span></div>' +
        '<input type="button" id="wpe_qt_content_strong" class="wpe_ed_button" title="" value="b">' + 
        '<input type="button" id="wpe_qt_content_em" class="wpe_ed_button" title="" value="i">' + 
        '<input type="button" id="wpe_qt_content_link" class="wpe_ed_button" title="" value="link">' + 
        '<input type="button" id="wpe_qt_content_block" class="ed_button" title="" value="b-quote">' + 
        '<input type="button" id="wpe_qt_content_del" class="ed_button" title="" value="del">' + 
        '<input type="button" id="wpe_qt_content_ins" class="ed_button" title="" value="ins">' + 
        '<input type="button" id="wpe_qt_content_img" class="ed_button" title="" value="img">' + 
        '<input type="button" id="wpe_qt_content_ul" class="ed_button" title="" value="ul">' + 
        '<input type="button" id="wpe_qt_content_ol" class="ed_button" title="" value="ol">' + 
        '<input type="button" id="wpe_qt_content_li" class="ed_button" title="" value="li">' + 
        '<input type="button" id="wpe_qt_content_code" class="ed_button" title="" value="code">' + 
        '<input type="button" id="wpe_qt_content_more" class="ed_button" title="" value="more">' + 
        '<input type="button" id="wpe_qt_content_page" class="ed_button" title="" value="page">' + 
        '<input type="button" id="wpe_qt_content_lookup" class="ed_button" title="" value="lookup">' + 
        '<input type="button" id="wpe_qt_content_fullscreen" class="ed_button" title="" value="fullscreen">' + 
        '</div>'
      ).height($('.CodeMirror').height() + 33);
      $('.CodeMirror-scroll').height($('.CodeMirror-wrap').height() - $('#wp-editor-quicktags').height() - 3);
      editor.focus();
    }
  }
})(jQuery);