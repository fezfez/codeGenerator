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
\t\t            <select name=\"backend\" id=\"form_Backend\" class=\"form-control\" ng-model=\"backEnd\" ng-change=\"backendChange()\" ng-options=\"obj.id as obj.label for obj in backendList\">
\t\t            <option value=\"\">Select Backend</option>
\t\t            </select>
            \t</div>
            \t<metadata></metadata>
            </form>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class=\"container row\">
            ";
        // line 46
        $this->displayBlock('content', $context, $blocks);
        // line 48
        echo "            <div id=\"test\" class=\"col-md-9\">
\t\t\t\t<file></file>
            </div>
            <div class=\"col-md-3\" id=\"div-questions\">
                <generators></generators>
                <questions></questions>
            </div>

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
    <script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/angularjs/1.2.6/angular.js\"></script>
    <script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 72
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "basepath"), "html", null, true);
        echo "/assets/js/Corp/Controllers.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 73
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "basepath"), "html", null, true);
        echo "/assets/js/Corp/Directive.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 74
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "basepath"), "html", null, true);
        echo "/assets/js/Vendor/bootstrap.min.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 75
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "basepath"), "html", null, true);
        echo "/assets/js/Vendor/shCore.js\"></script>
\t<script type=\"text/javascript\" src=\"";
        // line 76
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "basepath"), "html", null, true);
        echo "/assets/js/Vendor/shBrushPhp.js\"></script>
<style type=\"text/css\">
.file {
    padding:3px;

}
</style>
</body>
</html>";
    }

    // line 46
    public function block_content($context, array $blocks = array())
    {
        // line 47
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
        return array (  136 => 47,  133 => 46,  120 => 76,  116 => 75,  112 => 74,  108 => 73,  104 => 72,  78 => 48,  76 => 46,  48 => 20,  46 => 19,  38 => 14,  34 => 13,  20 => 1,);
    }
}
