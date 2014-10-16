define(
    ['Corp/Context/ContextHydrator', 'Corp/Context/Context'],
    function(ContextHydrator, Context)
{
    describe('Testing ContextHydrator', function() {

        it('Should hydrate backendCollection and default response', function() {
            var context         = new Context(),
                contextHydrator = new ContextHydrator(),
                results         = null,
                backendQuestion = {
                dtoAttribute : 'backend',
                    values : [{id : 'my id', 'label' : 'my label'}],
                    defaultResponse : 'my id'
                },
                rawData = {
                    question : [

                    ]
                };

            rawData.question.push(backendQuestion);

            results = contextHydrator.hydrate(rawData, context);
            expect(results.getBackendCollection()).toEqual(backendQuestion);
            expect(results.getBackend()).toEqual(backendQuestion.defaultResponse);
        });
        
        it('Should hydrate metadataCollection and default response', function() {
            var context         = new Context(),
                contextHydrator = new ContextHydrator(),
                results         = null,metadataQuestion = {
                    dtoAttribute : 'metadata',
                    values : [{id : 'my id', 'label' : 'my label'}],
                    defaultResponse : 'my id'
                },
                rawData = {
                    question : [

                    ]
                };

            rawData.question.push(metadataQuestion);

            results = contextHydrator.hydrate(rawData, context);
            expect(results.getMetadataCollection()).toEqual(metadataQuestion);
            expect(results.getMetadata()).toEqual(metadataQuestion.defaultResponse);
        });
        
        it('Should hydrate generatorCollection and default response', function() {
            var context         = new Context(),
                contextHydrator = new ContextHydrator(),
                results         = null,
                generatorQuestion = {
                    dtoAttribute : 'generator',
                    values : [{id : 'my id', 'label' : 'my label'}],
                    defaultResponse : 'my id'
                },
                rawData = {
                    question : [

                    ]
                };

            rawData.question.push(generatorQuestion);

            results = contextHydrator.hydrate(rawData, context);
            expect(results.getGeneratorCollection()).toEqual(generatorQuestion);
            expect(results.getGenerator()).toEqual(generatorQuestion.defaultResponse);
        });

        it('Should create relation beetween backend and metadata', function() {
            var context         = new Context(),
                contextHydrator = new ContextHydrator(),
                results         = null,
                backendQuestion = {
                    dtoAttribute : 'backend',
                    values : [{id : 'my id', label : 'asource'}],
                    defaultResponse : 'my id'
                },
                metadataQuestion = {
                    dtoAttribute : 'metadata',
                    values : [{id : 'my id', label : 'my label', source : 'asource'}],
                    defaultResponse : 'my id'
                },
                rawData = {
                    question : [

                    ]
                },
                backendCollection = undefined;

            rawData.question.push(metadataQuestion);
            rawData.question.push(backendQuestion);

            results = contextHydrator.hydrate(rawData, context);
            expect(results.getMetadataCollection()).toEqual(metadataQuestion);
            expect(results.getMetadata()).toEqual(metadataQuestion.defaultResponse);

            backendCollection = results.getBackendCollection();

            expect(backendCollection.values[0]).toEqual({
                id : 'my id',
                label : 'asource',
                metadata : {
                    values : [
                        {id : 'my id', label : 'my label', source : 'asource'}
                    ]
                }
            });

            expect(results.getBackend()).toEqual(backendQuestion.defaultResponse);
        });

        it('Should hydrate files', function() {
            var context         = new Context(),
                contextHydrator = new ContextHydrator(),
                results         = null,
                rawData         = {
                    files : [
                        {
                            fileName : 'test'
                        }
                    ]
                };

            results = contextHydrator.hydrate(rawData, context);

            expect(results.getDirectories().getFiles()[0].getName()).toEqual('test');
        });
    });
});