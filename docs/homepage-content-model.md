# Homepage content model

The homepage is intentionally content-driven:

1. A sticky post, or otherwise the latest published post, becomes the featured story.
2. Five additional posts are selected from recent content, shuffled in PHP and cached for six hours.
3. The latest six published guides remain chronological.
4. Food-family navigation comes from the category hierarchy.
5. Informational navigation comes from the independent `food_topic` taxonomy.

The random selection cache is invalidated when posts are saved or deleted.
