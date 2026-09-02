# FOOD · arquitectura editorial

FOOD se diseña como una plataforma de alimentación que empieza por contenido SEO. En esta fase no hay tienda, vendedores ni productos: el objetivo es construir autoridad, tráfico y una experiencia editorial que pueda convivir más adelante con un marketplace sin rehacer la marca ni la navegación.

## Principio de clasificación

Cada artículo debe tener una categoría principal según la intención dominante de la búsqueda.

- Si la duda trata principalmente sobre un producto concreto, va en su familia dentro de **Alimentos**.
- Si la pregunta principal es si algo es seguro, está estropeado o se puede comer, va en **Seguridad alimentaria**.
- Si compara proteína, grasa, fibra, calorías o composición, va en **Nutrición**.
- Si explica una técnica, un error al cocinar o qué ocurre durante la cocción, va en **Cocina**.
- Si trata sobre cómo construir comidas, platos completos o menús, va en **Platos y menús**.
- Si la intención principal es DOP, sellos, etiquetado, procedencia o criterios de calidad, va en **Origen y calidad**.

No se crean categorías nuevas para cada consulta. Primero se intenta encajar la consulta en esta arquitectura.

## Categorías principales

- **Alimentos**
  - Carnes
  - Pescados y mariscos
  - Jamón y embutidos
  - Quesos y lácteos
  - Aceites
  - Legumbres
  - Frutas
  - Verduras y hortalizas
  - Cereales, pan y pasta
  - Huevos
- **Seguridad alimentaria**
- **Nutrición**
- **Cocina**
- **Platos y menús**
- **Origen y calidad**

## Ejemplos de decisión

| Consulta | Categoría recomendada | Motivo |
| --- | --- | --- |
| ¿Por qué amarga el aceite de oliva? | Aceites | La intención principal es entender una característica del producto. |
| ¿Se puede comer una berenjena negra por dentro? | Seguridad alimentaria | La decisión buscada es si el alimento es seguro o debe descartarse. |
| ¿Por qué la carne suelta agua en la sartén? | Cocina | La pregunta busca explicar un fenómeno de cocción y cómo evitarlo. |
| Carnes con más proteína y menos grasa | Nutrición | La intención es comparar composición nutricional. |
| Denominaciones de origen del jamón ibérico | Jamón y embutidos | El usuario busca información específica sobre una familia de producto. |
| ¿Qué significa una DOP? | Origen y calidad | La consulta es transversal y trata sobre el sistema de calidad. |
| Ideas de comidas con mucha proteína | Platos y menús | La intención es construir comidas, no analizar un ingrediente aislado. |

## Tags

FOOD no utiliza tags como elemento visible de navegación. Las categorías son suficientes para la arquitectura editorial inicial y evitan taxonomías duplicadas, archivos débiles y nubes de etiquetas difíciles de mantener.

Los archivos de tags existentes se marcan como `noindex` y los nuevos artículos deben publicarse sin etiquetas.

## Contenido evergreen

Las entradas se presentan como guías permanentes:

- No se muestra la fecha de publicación en portada, tarjetas ni cabecera del artículo.
- Las URLs usan `/%postname%/` y no contienen año, mes o día.
- WordPress conserva internamente `datePublished` y `dateModified` para datos estructurados y mantenimiento SEO.
- Cuando una guía quede desactualizada, se actualiza el artículo existente en vez de crear una nueva URL.

## Relación con el futuro marketplace

En esta fase no se muestran productos ni llamadas de compra. Aun así, la arquitectura se prepara para el futuro:

- **Alimentos** funciona visualmente como una navegación por familias de producto.
- **Platos y menús** permite construir autoridad alrededor de la necesidad que más adelante cubrirán los platos preparados.
- Los artículos deben poder enlazar en el futuro a productos, productores o colecciones relevantes sin cambiar su intención informativa.
- WooCommerce/WCFM y cualquier estructura de vendedores se incorporarán solo cuando comience la fase marketplace.

El objetivo editorial es que FOOD no parezca hoy una tienda vacía ni mañana un ecommerce pegado a un blog: debe sentirse como una misma plataforma en ambas fases.
