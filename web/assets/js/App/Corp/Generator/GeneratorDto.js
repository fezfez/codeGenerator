define(function() {

    function GeneratorDto() {
        var id     = null,
            label  = null,
            github = null,
            readme = null;

        this.setId = function(value) {
            id = value;
            return this;
        };
        this.setLabel = function(value) {
            label = value;
            return this;
        };
        this.setGihub = function(value) {
            github = value;
            return this;
        };
        this.setReadme = function(value) {
            readme = value;
            return this;
        };

        this.getId = function() {
            return id;
        };
        this.getLabel = function() {
            return label;
        };
        this.getGithub = function() {
            return github;
        };
        this.getReadme = function() {
            return readme;
        };
    }

    return GeneratorDto;
});