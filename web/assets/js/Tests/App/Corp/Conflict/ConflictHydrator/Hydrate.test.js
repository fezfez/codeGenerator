define(function(require) {
    'use strict';

    var ConflictHydrator = require('Corp/Conflict/ConflictHydrator');

    describe('Testing Corp/Conflict/ConflictHydrator method hydrate', function() {

        it('Should hydrate backendCollection and default response', function() {
            var conflictHydrator = new ConflictHydrator();

            conflictHydrator.hydrate([{'terfzefezfze': 'zefezfez'}]);
        });
    });
});