# FOOD — Tema WordPress editorial para contenido SEO gastronómico

Tema WordPress ligero y responsive pensado para un portal de contenidos sobre alimentos, producto, cocina, nutrición práctica y seguridad alimentaria.

## Enfoque

FOOD está diseñado para responder búsquedas informativas concretas y, al mismo tiempo, construir autoridad temática mediante categorías de producto.

Categorías sugeridas:

- Carnes
- Jamón
- Quesos
- Aceites
- Legumbres
- Frutas y verduras
- Seguridad alimentaria

Ejemplos de contenidos:

- ¿Se puede comer una patata verde?
- ¿Qué significa que una patata esté negra por dentro?
- ¿Por qué la carne suelta agua en la sartén?
- Carnes con más proteína y menos grasa
- Denominaciones de origen del jamón en España
- Cómo conservar correctamente un queso abierto
- Diferencias entre aceite virgen y virgen extra

## Instalación

1. Descarga el repositorio como ZIP.
2. En WordPress ve a Apariencia → Temas → Añadir nuevo → Subir tema.
3. Activa FOOD.
4. En Ajustes → Lectura, configura la portada para que WordPress use la página principal del tema.
5. Crea las categorías y un menú principal en Apariencia → Menús. Si no hay menú configurado, el tema muestra una navegación de respaldo.

## Cómo publicar para aprovechar la plantilla SEO

Cada entrada debería tener:

- **Título:** la consulta principal, de forma natural.
- **Extracto:** una respuesta directa de 1–3 frases. El tema la muestra como bloque “Respuesta rápida” bajo el título.
- **Imagen destacada:** se usa en portada, listados y cabecera del artículo.
- **Contenido:** respuesta desarrollada con H2/H3, tablas y listas cuando aporten claridad.
- **Categoría:** una categoría principal de producto o temática.
- **Etiquetas:** opcionales para conceptos transversales.

El tema incluye marcado JSON-LD básico de `Article`. Si se instala un plugin SEO que genere el mismo marcado, conviene desactivar una de las dos salidas para evitar duplicados.

## Publicidad / AdSense

El tema registra áreas de widgets específicas para insertar publicidad desde WordPress:

- `Publicidad · portada`
- `Publicidad · artículo`

Si todavía no hay un widget publicitario configurado, la portada muestra un marcador discreto para visualizar el espacio disponible.

## Diseño

- Fondo crema y estética editorial.
- Verde oliva oscuro como color principal.
- Terracota para acentos y categorías.
- Titulares serif y textos de interfaz sans-serif.
- Portada tipo revista con buscador protagonista.
- Tarjetas responsive de artículos.
- Plantilla de artículo optimizada para lectura larga.
- Menú móvil accesible.
- Paleta integrada en el editor de bloques mediante `theme.json`.

## Archivos principales

- `front-page.php` — portada editorial.
- `single.php` — entrada SEO.
- `archive.php` — categorías, etiquetas y resultados.
- `page.php` — páginas estáticas.
- `functions.php` — configuración del tema, menús, widgets y schema Article.
- `style.css` — identidad visual y responsive.
- `theme.json` — estilos del editor de bloques.

## Próximos pasos recomendados

1. Crear las categorías definitivas y sus textos introductorios.
2. Publicar 10–20 artículos iniciales agrupados en 2–3 clusters temáticos.
3. Crear páginas legales, contacto, metodología editorial y autores.
4. Añadir un plugin SEO para sitemaps, metadatos y schema avanzado si se necesita.
5. Configurar analítica, Search Console y AdSense cuando el dominio esté listo.
