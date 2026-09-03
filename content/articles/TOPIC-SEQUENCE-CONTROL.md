# Quinnoa — control de secuencia de artículos

Este fichero existe para impedir que la numeración editorial se complete por inferencia.

## Fuente de verdad

La secuencia canónica completa está en:

`content/articles/TOPIC-MASTER-LIST.md`

Contiene los **635 temas aprobados por el usuario**. Antes de crear, corregir o continuar un artículo hay que leer de ahí el número y título exactos.

## Reglas obligatorias

- Crear los artículos únicamente en el orden del listado maestro.
- No elegir el siguiente tema buscando huecos SEO.
- No inventar, sustituir, fusionar, saltar ni reordenar temas.
- El listado maestro prevalece sobre cualquier JSON existente que entre en conflicto con él.
- Español e inglés deben compartir `article_number` y `translation_group`; el inglés se localiza de forma independiente, sin cambiar la intención aprobada.
- Los checkpoints de 10–15 artículos son solo controles de validación; no obligan a detener la generación.

## Estado de corrección detectado el 3 de septiembre de 2026

El listado maestro confirmó que los JSON existentes **167–170** no correspondían a los títulos aprobados. Deben reemplazarse por:

167. Omega-3 vegetal vs. omega-3 del pescado
168. Aguacate vs. plátano: potasio, fibra y calorías
169. Almendras vs. pistachos: proteína, fibra y calorías
170. Carne de pollo: proteína, grasa y calorías según el corte

Los artículos 171–185 sí coinciden con el maestro. Una vez corregido 167–170, el siguiente artículo nuevo es:

186. Salmón vs. sardinas: omega-3, proteína y calorías

## Borradores no canónicos

Cualquier borrador 186+ preparado antes de versionar `TOPIC-MASTER-LIST.md` debe ignorarse salvo que su número y tema coincidan exactamente con el fichero maestro y se vuelva a revisar con el estándar editorial vigente.
