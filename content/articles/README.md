# FOOD — artículos estructurados

Esta carpeta contiene los artículos editoriales de FOOD en JSON, preparados para importación posterior a WordPress.

## Organización

- `es/`: artículos en español, orientados prioritariamente a España/UE cuando existe normativa o guía regional relevante.
- `en/`: artículos en inglés, redactados de forma independiente y orientados prioritariamente a una audiencia internacional con referencias estadounidenses cuando son útiles.
- Cada concepto comparte `translation_group`, pero las versiones no son traducciones literales.
- Los identificadores `article_number` conservan la numeración del mapa editorial maestro.

## Criterio editorial

Los artículos deben resolver la consulta desde el principio, desarrollar el tema con contexto y matices, evitar afirmaciones absolutas que no se sostengan y distinguir entre datos por 100 g, por ración, alimento crudo/seco y alimento cocinado cuando esa diferencia pueda inducir a error.

En seguridad alimentaria se priorizan fuentes oficiales. En nutrición se priorizan bases de composición y organismos públicos. Las cifras nutricionales se presentan como aproximadas porque cambian con variedad, corte, marca y preparación.

## Taxonomías

Se mantienen dos clasificaciones independientes:

1. `food_family`: de qué alimento o familia trata el artículo.
2. `article_type`: qué intención editorial resuelve.

Los nombres y claves canónicas están definidos en `taxonomies.json`.

## Publicación

Los JSON se guardan inicialmente con `status: draft`. El futuro importador debe ser idempotente y usar `id`/`slug` para crear o actualizar el artículo sin duplicarlo. El campo `content_html` está preparado para insertarse como `post_content` de WordPress.
