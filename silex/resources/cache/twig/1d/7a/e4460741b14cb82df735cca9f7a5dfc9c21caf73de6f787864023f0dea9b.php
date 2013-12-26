<?php

/* layout.html.twig */
class __TwigTemplate_1d7ae4460741b14cb82df735cca9f7a5dfc9c21caf73de6f787864023f0dea9b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"en\"  ng-app=\"GeneratorApp\">
  <head>
    <meta charset=\"utf-8\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <meta name=\"description\" content=\"\">
    <meta name=\"author\" content=\"\">
    <link rel=\"shortcut icon\" href=\"../../docs-assets/ico/favicon.png\">

    <title>Code Generator</title>

    <link rel=\"stylesheet\" href=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "basepath"), "html", null, true);
        echo "/assets/css/bootstrap.min.css\">
    <link rel=\"stylesheet\" href=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "basepath"), "html", null, true);
        echo "/assets/css/shCoreDefault.css\">
    <script src=\"//cdnjs.cloudflare.com/ajax/libs/modernizr/2.7.1/modernizr.min.js\"></script>
</head>
<body ng-controller=\"GeneratorCtrl\">
<!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href=\"http://browsehappy.com/\">Upgrade to a different browser</a> or <a href=\"http://www.google.com/chromeframe/?redirect=true\">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
    ";
        // line 19
        $context["active"] = ((array_key_exists("active", $context)) ? (_twig_default_filter((isset($context["active"]) ? $context["active"] : $this->getContext($context, "active")), null)) : (null));
        // line 20
        echo "    <div class=\"navbar navbar-inverse\" role=\"navigation\">
      <div class=\"container\">
        <div class=\"navbar-header\">
          <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\".navbar-collapse\">
            <span class=\"sr-only\">Toggle navigation</span>
            <span class=\"icon-bar\"></span>
            <span class=\"icon-bar\"></span>
            <span class=\"icon-bar\"></span>
          </button>
          <a class=\"navbar-brand\" href=\"#\">Code Generator</a>
        </div>
        <div class=\"collapse navbar-collapse\">
        \t<form class=\"navbar-form navbar-right\" role=\"form\" id=\"formBackend\">
        \t\t<div class=\"form-group\">
\t\t            Backend :
\t\t            <select name=\"backend\" class=\"form-control\" ng-model=\"backEnd\" ng-change=\"change()\" ng-options=\"obj.id as obj.label for obj in backendList\">
\t\t            <option value=\"\">Select Backend</option>
\t\t            </select>
            \t</div>
            </form>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class=\"container\">
            ";
        // line 45
        $this->displayBlock('content', $context, $blocks);
        // line 47
        echo "            <div id=\"test\" class=\"row\">

            </div>
            <form id=\"questions\">
            </form>
    </div>
    <!-- Modal -->
<div class=\"modal fade\" id=\"configuration-modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"configuration-modal\" aria-hidden=\"true\">
  <div class=\"modal-dialog\">
    <div class=\"modal-content\">
      <div class=\"modal-header\">
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
        <h4 class=\"modal-title\" id=\"myModalLabel\">Configuration</h4>
      </div>
      <div class=\"modal-body\">
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    <script type=\"text/javascript\" src=\"http://code.jquery.com/jquery-2.0.3.min.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 67
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "basepath"), "html", null, true);
        echo "/assets/js/Vendor/angular.min.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 68
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "basepath"), "html", null, true);
        echo "/assets/js/Corp/Controllers.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 69
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "basepath"), "html", null, true);
        echo "/assets/js/Vendor/bootstrap.min.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 70
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "basepath"), "html", null, true);
        echo "/assets/js/Vendor/shCore.js\"></script>
\t<script type=\"text/javascript\" src=\"";
        // line 71
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "basepath"), "html", null, true);
        echo "/assets/js/Vendor/shBrushPhp.js\"></script>
    <script type=\"text/javascript\">

function swapJsonKeyValues(input) {
    var one, output = {};
    for (one in input) {
        if (input.hasOwnProperty(one)) {
            output[input[one]] = one;
        }
    }
    return output;
}

var generator = {
\toldGenerator : null,
\tajax : function() {
\t\tvar self = this;
\t\t\$.ajax({
\t        type: \"POST\",
\t        url: \"generator\",
\t        data: {
\t            generator: \$('#form_Generator').val(),
\t            backend: \$('#form_Backend').val(),
\t            metadata : \$('#form_Metadata').val(),
\t            questions : \$('#questions').serialize()
\t        }
\t    })
\t    .done(function( data ) {

\t        var countProfondeurMax = null,
\t            profondeur = null,
\t            profondeurFiles = new Array(),
\t            filesByValue = swapJsonKeyValues(data.generator.files);
\t        \$.each(data.generator.files, function(id, name) {

\t            if(typeof name != 'string') {
\t                return;
\t            }
\t            profondeur = name.split('/').length;

\t            if(countProfondeurMax < profondeur) {
\t                countProfondeurMax = profondeur;
\t            }
\t            if(profondeurFiles[profondeur] === undefined) {
\t                profondeurFiles[profondeur] = new Array();
\t            }
\t            profondeurFiles[profondeur].push(name);
\t        });

\t        if(self.oldGenerator !== \$('#form_Generator').val()) {
\t\t        \$.each(data.generator.questions, function(id, name) {
\t\t\t\t\t\$('#questions').append(
\t\t\t\t         '<div class=\"form-group\">'+
\t\t\t\t            '<label for=\"' + name.dtoAttribute + '\">' + name.text + '</label>'+
\t\t\t\t\t\t\t'<input class=\"form-control\" id=\"' + name.dtoAttribute + '\" type=\"text\" name=\"' + name.dtoAttribute + '\" placeholder=\"' + name.text + '\" />' +
\t\t\t\t\t\t'</div>'
\t\t\t\t\t);
\t\t        });
\t\t        \$('#questions input').on('keyup', function() {
\t\t\t\t\tself.ajax();
\t\t\t    });
\t        }

\t        \$('#test').empty();
\t        var countFile = 0;
\t        for(var i = 1 ; i <= countProfondeurMax ; i++){
\t            if(profondeurFiles[i] !== undefined) {
\t            \t\$('#test').append('<div class=\"col-lg-' + Math.floor(12 / countProfondeurMax) + '\" id=\"test-' + i +'\"></div>');
\t                \$.each(profondeurFiles[i], function(id, name) {
\t                    var tmp = \$('#test-' + i + '').append('<div class=\"file\" id=\"file-' + '-' + countFile + '\">' + name + '</div>');
\t                    \$('#file-' + '-' + countFile).on('click', function() {
\t                        \$.ajax({
\t                            type: \"POST\",
\t                            url: \"view-file\",
\t                            data : {
\t                                generator: \$('#form_Generator').val(),
\t                                file : filesByValue[name],
\t                                backend : \$('#form_Backend').val(),
\t                                metadata : \$('#form_Metadata').val(),
\t                \t            questions : \$('#questions').serialize()
\t                            }
\t                        }).done(function(data) {
\t                            \$('#configuration-modal .modal-title').empty().append(name);
\t                            \$('#configuration-modal .modal-body').empty().append('<pre class=\"brush: php;\">' + data.generator + '<pre>');
\t                            \$('#configuration-modal').modal('show');
\t                            SyntaxHighlighter.highlight();
\t                        });
\t                    });
\t                    countFile++;
\t                });
\t            }
\t        }

\t        self.oldGenerator = \$('#form_Generator').val();
\t    });
\t}
};
\$('#form_Generator').on('change', function() {
\tgenerator.ajax();
});
</script>
<style type=\"text/css\">
.file {
    padding:3px;

}
</style>
</body>
</html>";
    }

    // line 45
    public function block_content($context, array $blocks = array())
    {
        // line 46
        echo "            ";
    }

    public function getTemplateName()
    {
        return "layout.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  231 => 46,  228 => 45,  115 => 71,  111 => 70,  107 => 69,  103 => 68,  99 => 67,  77 => 47,  75 => 45,  48 => 20,  46 => 19,  38 => 14,  34 => 13,  20 => 1,);
    }
}
