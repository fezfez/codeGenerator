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
          <div class=\"form-group\">
              Backend :
              <select name=\"backend\" class=\"form-control\" ng-model=\"backEnd\" ng-change=\"change()\" ng-options=\"obj.id as obj.label for obj in backendList\">
              <option value=\"\">Select Backend</option>
              </select>
            </div>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class=\"container\">
            ";
        // line 43
        $this->displayBlock('content', $context, $blocks);
        // line 45
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
    <script src=\"http://code.jquery.com/jquery-2.0.3.min.js\"></script>
    <script src=\"";
        // line 65
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "basepath"), "html", null, true);
        echo "/assets/js/angular.min.js\"></script>
    <script src=\"";
        // line 66
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "basepath"), "html", null, true);
        echo "/assets/js/bootstrap.min.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 67
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "basepath"), "html", null, true);
        echo "/assets/js/shCore.js\"></script>
\t<script type=\"text/javascript\" src=\"";
        // line 68
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "basepath"), "html", null, true);
        echo "/assets/js/shBrushPhp.js\"></script>
    <script type=\"text/javascript\">


    // Define our AngularJS application module.
    var GeneratorApp = angular.module( \"GeneratorApp\", [] );

    GeneratorApp.controller(\"GeneratorCtrl\", function(\$scope, \$http) {
    \t\$scope.backendList = [{id : 'myValue', label : 'myText'}];
    \t\$scope.change = function() {
\t\t\tconsole.log(\$scope.backEnd);
\t\t    \$http(
\t\t\t\t{
\t\t\t\t\tmethod: 'POST',
\t\t\t\t\turl: 'metadata',
\t\t\t\t\tdata : {
\t\t\t\t\t\tbackend : \$scope.backEnd
\t\t\t\t\t}
\t\t\t\t}
\t\t\t).success(function(data, status, headers, config) {
\t\t    // this callback will be called asynchronously
\t\t    // when the response is available
\t\t    }).error(function(data, status, headers, config) {
\t\t    // called asynchronously if an error occurs
\t\t    // or server returns response with an error status.
\t\t    });
        };
\t});


\$('#form_Backend').on('change', function() {
    \$.ajax({
        type: \"POST\",
        url: \"metadata\",
        data: { backend: \$('#form_Backend').val() }
    })
    .done(function( msg ) {
            if(msg.config !== undefined) {
                \$('#configuration-modal .modal-body').empty(); \$('#configuration-modal .modal-body')
                \$('#configuration-modal .modal-body').append(msg.config);
                \$('#configuration-modal').modal('show');
                \$(\"#configuration-modal form\").submit(function(){
                    \$.ajax({
                        type:\"POST\",
                        data: \$(this).serialize() + '&'+\$('#form_Backend').serialize(),
                        url : \$(this).attr('action')
                    }).done(function(data){
                        if (data.error !== undefined) {
                            \$('#alert-config').remove();
                            \$(\"#configuration-modal .modal-body\").prepend('<div id=\"alert-config\" class=\"alert alert-danger fade in\">' + data.error + '</div>');
                        } else {
                            \$('#configuration-modal').modal('hide');
                        }
                    });
                    return false;
                });
            } else if (msg.metadatas !== undefined) {
                var metadatasString = '';
                \$.each(msg.metadatas, function(name) {
                    metadatasString += '<option value=\"' + name +'\">' + name + '</option>';
                });
                \$('#form').append(
                    '<div class=\"control-group\">'+
                        '<label for=\"form_Backend\" class=\"control-label required\">Metadata</label>'+
                        '<div class=\"controls\">'+
                            '<select name=\"form[Metadata]\" id=\"form_Metadata\">'+
                                metadatasString +
                            '</select>'+
                        '</div>'+
                    '</div>'
                );
            }
        }
    );
});
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

    // line 43
    public function block_content($context, array $blocks = array())
    {
        // line 44
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
        return array (  297 => 44,  294 => 43,  109 => 68,  105 => 67,  101 => 66,  97 => 65,  75 => 45,  73 => 43,  48 => 20,  46 => 19,  38 => 14,  34 => 13,  20 => 1,);
    }
}
