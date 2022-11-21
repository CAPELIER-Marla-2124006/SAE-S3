define("ace/theme/perso",["require","exports","module","ace/lib/dom"], function(require, exports, module) {

exports.isDark = true;
exports.cssClass = "ace-perso";
exports.cssText = "\
.ace-perso .ace_gutter {/*colone de gauche (nb lines)*/\
    background: hsl(0, 0%, 24%);\
    color: var(--accent-color);\
}\
\
.ace-perso .ace_print-margin {/*barre des 80 char*/\
    width: 1px;\
    background: var(--accent-color);\
}\
\
.ace-perso {/*global, background & text*/\
    background-color: hsl(0, 0%, 20%);\
    color: hsl(162, 30%, 94%);\
}\
\
.ace-perso .ace_cursor {/*cursor color*/\
    color: hsl(162, 30%, 94%);\
}\
\
.ace-perso .ace_marker-layer .ace_selection {/*selection highlight*/\
    background: hsl(162, 10%, 32%)\
}\
\
.ace-perso.ace_multiselect .ace_selection.ace_start {/*shadow when selecting mutliple things*/\
    box-shadow: 0 0 3px 0px hsl(0, 0%, 15%);\
}\
\
.ace-perso .ace_marker-layer .ace_step {/*jsp*/\
    background: hsl(48, 100%, 20%)\
}\
\
.ace-perso .ace_marker-layer .ace_bracket {/**/\
    margin: -1px 0 0 -1px;\
    border: 1px solid hsl(0, 0%, 25%)\
}\
\
.ace-perso .ace_marker-layer .ace_active-line {\
    background: hsl(0, 0%, 28%)\
}\
\
.ace-perso .ace_gutter-active-line {\
    background-color: hsl(0, 0%, 16%)\
}\
\
.ace-perso .ace_marker-layer .ace_selected-word {\
    border: 1px solid hsl(0, 0%, 25%)\
}\
\
.ace-perso .ace_invisible {\
    color: hsl(162, 5%, 30%)\
}\
\
.ace-perso .ace_entity.ace_name.ace_tag,\
.ace-perso .ace_keyword,\
.ace-perso .ace_meta.ace_tag,\
.ace-perso .ace_storage {\
    color: var(--accent-color);\
    font-weight: 900;\
}\
.ace-perso .ace_operator {\
    font-weight: 400;\
}\
\
.ace-perso .ace_punctuation,\
.ace-perso .ace_punctuation.ace_tag {\
    color: hsl(162, 30%, 94%);\
}\
\
.ace-perso .ace_constant.ace_character,\
.ace-perso .ace_constant.ace_language,\
.ace-perso .ace_constant.ace_numeric,\
.ace-perso .ace_constant.ace_other {\
    color: hsl(0, 100%, 65%)\
}\
\
.ace-perso .ace_invalid {\
    color: hsl(162, 30%, 94%);\
    background-color: var(--accent-color);\
}\
\
.ace-perso .ace_invalid.ace_deprecated {\
    color: hsl(162, 30%, 94%);\
    background-color: hsl(0, 100%, 65%)\
}\
\
.ace-perso .ace_support.ace_constant,\
.ace-perso .ace_support.ace_function {\
    color: hsl(190, 81%, 67%)\
}\
\
.ace-perso .ace_fold {\
    background-color: var(--accent-color);\
    border-color: hsl(162, 30%, 94%);\
}\
\
.ace-perso .ace_storage.ace_type,\
.ace-perso .ace_support.ace_class,\
.ace-perso .ace_support.ace_type {\
    font-style: italic;\
    color: hsl(190, 81%, 67%)\
}\
\
.ace-perso .ace_entity.ace_name.ace_function,\
.ace-perso .ace_entity.ace_other,\
.ace-perso .ace_entity.ace_other.ace_attribute-name,\
.ace-perso .ace_variable {\
    color: hsl(80, 76%, 53%)\
}\
\
.ace-perso .ace_variable.ace_parameter {\
    font-style: italic;\
    color: hsl(32, 98%, 56%)\
}\
\
.ace-perso .ace_string {\
    font-style: italic;\
    color: hsl(180, 90%, 60%)\
}\
\
.ace-perso .ace_comment {\
    font-style: italic;\
    color: hsl(50, 11%, 41%)\
}\
\
.ace-perso .ace_indent-guide {\
    background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAACCAYAAACZgbYnAAAAEklEQVQImWPQ0FD0ZXBzd/wPAAjVAoxeSgNeAAAAAElFTkSuQmCC) right repeat-y\
}\
";

var dom = require("../lib/dom");
dom.importCssString(exports.cssText, exports.cssClass, false);
});                (function() {
                    window.require(["ace/theme/perso"], function(m) {
                        if (typeof module == "object" && typeof exports == "object" && module) {
                            module.exports = m;
                        }
                    });
                })();
