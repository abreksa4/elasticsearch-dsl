# Elasticsearch DSL

The following documents are usage examples of the library, they focus on each query class
individually, but keep in mind that building a query using the fluent interface you will never have
to create instances. Queries are usually built for a `Query` instance, we recommend that you first
have a look at the [Query][1] chapter.

- Query DSL
    - Full text queries
        - [match](Query/Text/Match.md)
        - [match_all](Query/Text/MatchAll.md)
        - [match_none](Query/Text/MatchNone.md)
        - [match_phrase](Query/Text/MatchPhrase.md)
        - [match_phrase_prefix](Query/Text/MatchPhrasePrefix.md)
        - [multi_match](Query/Text/MultiMatch.md)

[1]: Query.md