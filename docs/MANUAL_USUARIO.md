# Manual de Usuario — TEC-SGI 2026

**Sistema de Gestión de Incidencias del Instituto TEC**

Este manual está dirigido a docentes, personal administrativo, técnicos, directivos y administradores del Instituto TEC. Su objetivo es explicar, de forma sencilla, cómo usar las funciones principales del sistema.

---

## 1. Introducción

### 1.1 ¿Qué es TEC-SGI 2026?

TEC-SGI 2026 es el Sistema de Gestión de Incidencias del Instituto TEC. Permite registrar problemas relacionados con equipos, aulas, oficinas y servicios tecnológicos, hacer seguimiento a cada caso y conservar un historial de atención.

Con TEC-SGI 2026 puedes:

- Reportar una incidencia desde cualquier navegador.
- Consultar el estado de tus tickets.
- Comunicarte con el técnico que atiende el caso.
- Consultar artículos de ayuda publicados por el área de TI.
- Mantener organizado el inventario de equipos.
- Revisar estadísticas de atención para la dirección.
- Administrar usuarios y roles del sistema.

### 1.2 Perfiles de usuario

| Perfil | Funciones principales |
| --- | --- |
| **Usuario** (docente o administrativo) | Reportar incidencias, consultar sus tickets y leer la Base de Conocimientos publicada. |
| **Técnico** | Atender tickets, asignarse casos, cambiar estados, registrar soluciones, agregar equipos al inventario y crear artículos de conocimiento. |
| **Directivo** | Consultar reportes y estadísticas, y leer la Base de Conocimientos. |
| **Administrador** | Administrar usuarios, inventario, tickets, reportes y Base de Conocimientos. |

Cada usuario verá únicamente las opciones que corresponden a su perfil.

### 1.3 Recomendaciones generales

- Usa siempre tu cuenta personal. No compartas tu contraseña.
- Cierra sesión al terminar, especialmente en computadoras compartidas.
- Describe el problema con la mayor cantidad de detalles posible.
- Antes de crear un ticket, revisa la Base de Conocimientos por si la solución ya está publicada.
- Si recibes un número de ticket, guárdalo: facilita el seguimiento.

---

## 2. Guía para Usuarios (Docentes y Administrativos)

### 2.1 Cómo iniciar sesión

1. Abre el navegador y entra a la dirección del sistema proporcionada por el Instituto TEC.
2. En la pantalla de bienvenida, haz clic en **Iniciar Sesión**.
3. Escribe tu **correo electrónico institucional**.
4. Escribe tu **contraseña**.
5. Haz clic en **Log in** para ingresar.

> **Nota:** si olvidaste tu contraseña, usa la opción **Forgot your password?** de la pantalla de acceso o comunícate con el área de TI del Instituto TEC.

### 2.2 Cómo reportar una incidencia

1. En el menú lateral, selecciona **Reportar Incidencia**.
2. Completa el formulario:

   | Campo | Qué debes indicar |
   | --- | --- |
   | **Ubicación donde ocurre el problema** | Aula, laboratorio, oficina o ambiente donde se presenta la falla. |
   | **Categoría de la incidencia** | Tipo de problema: equipo, red, software, impresora, etc. |
   | **Equipo afectado (Opcional)** | Selecciona el equipo si aparece en el inventario. |
   | **Descripción detallada del problema** | Explica qué ocurre, desde cuándo y qué estabas haciendo. Mínimo 10 caracteres. |
   | **Prioridad sugerida** | Baja, Media, Alta o Crítica, según el impacto del problema. |

3. Revisa la información antes de enviarla.
4. Haz clic en **Registrar Ticket**.
5. El sistema generará un número de ticket con el formato **TKT-AÑO-####**. Anótalo o conserva el comprobante.

> **Consejo:** mientras más clara sea la descripción, más rápida será la atención. Ejemplo: “La computadora del Laboratorio 2 no enciende desde esta mañana; al presionar el botón de encendido no muestra ninguna luz”.

### 2.3 Cómo consultar el estado de tus tickets

1. Inicia sesión con tu cuenta.
2. Abre la sección **Bandeja de Tickets**.
   - Si no ves esa opción en el menú, abre la dirección `/tickets` en el navegador después de iniciar sesión o solicita apoyo al área de TI.
3. La bandeja muestra únicamente los tickets que tú reportaste.
4. Puedes usar los filtros para encontrar un ticket:

   - **Buscar:** por número de ticket o descripción.
   - **Estado:** Abierto, En Progreso, En Espera, Resuelto o Cerrado.
   - **Prioridad:** Baja, Media, Alta o Crítica.

5. Haz clic en el número de ticket para abrir el detalle.
6. En el detalle puedes revisar:

   - Número de ticket.
   - Estado actual.
   - Prioridad.
   - Técnico asignado.
   - Equipo y ubicación.
   - Descripción del problema.
   - Comentarios e historial de atención.

#### Significado de los estados

| Estado | Significado |
| --- | --- |
| **Abierto** | El ticket fue registrado y está pendiente de atención. |
| **En Progreso** | Un técnico ya está trabajando en el caso. |
| **En Espera** | La atención está pausada temporalmente, por ejemplo, mientras se espera información o un repuesto. |
| **Resuelto** | El problema fue atendido y solucionado. |
| **Cerrado** | El caso terminó y ya no requiere acciones. |

### 2.4 Cómo consultar la Base de Conocimientos

1. En el menú lateral, abre **Consulta** y selecciona **Base de Conocimientos**.
2. Usa el buscador para escribir palabras relacionadas con tu problema.
3. Puedes filtrar por **categoría** y por **tipo** de artículo:
   - **FAQ:** preguntas frecuentes.
   - **Solución temporal:** pasos para continuar trabajando mientras se corrige el problema.
   - **Error conocido:** fallas identificadas con una explicación o solución.
4. Haz clic en un artículo para leer su contenido.
5. Si no encuentras una solución, crea un ticket desde **Reportar Incidencia**.

> **Importante:** los usuarios normales solo ven artículos publicados. Los borradores son visibles únicamente para técnicos y administradores.

---

## 3. Guía para Técnicos

### 3.1 Cómo ver la bandeja de tickets

1. Inicia sesión con tu cuenta de técnico.
2. En el menú lateral, abre **Gestión TI** y selecciona **Bandeja de Tickets**.
3. La bandeja muestra:
   - Los tickets que ya tienes asignados.
   - Los tickets que aún no tienen técnico asignado.
4. Usa los filtros de búsqueda, estado y prioridad para organizar tu trabajo.
5. Haz clic en el número de ticket para abrir el detalle.

### 3.2 Cómo asignarte un ticket

1. Abre un ticket que no tenga técnico asignado.
2. Revisa la descripción, la ubicación y el equipo afectado.
3. Haz clic en **Asignarme este ticket**.
4. El sistema:
   - Te asigna el ticket.
   - Cambia el estado a **En Progreso**.
   - Registra la fecha de primera respuesta.
   - Agrega el movimiento al historial del ticket.

> **Recomendación:** antes de asignarte un ticket, verifica que sea parte de tu especialidad o turno.

### 3.3 Cómo cambiar el estado de un ticket

1. Abre un ticket que esté asignado a tu usuario.
2. En la sección de acciones, ubica el selector **Estado**.
3. Selecciona el nuevo estado:
   - **En Progreso:** estás trabajando en el caso.
   - **En Espera:** necesitas información, un repuesto o una coordinación.
   - **Resuelto:** el problema ya fue solucionado.
4. Haz clic en **Cambiar estado**.

> **Nota:** el selector de estado solo aparece para el técnico asignado. Si el ticket está asignado a otra persona, coordina con ella o con el administrador.

Al cambiar a **Resuelto**, el sistema registra la fecha de resolución y actualiza los reportes.

### 3.4 Cómo registrar la solución

El sistema no tiene un campo separado llamado “solución”; la solución se documenta en los comentarios del ticket.

1. Abre el ticket asignado.
2. En el área de comentarios, escribe el diagnóstico y los pasos realizados. Ejemplo:
   - “Se reinició el equipo y se verificó el cable de red.”
   - “Se reemplazó el cable de alimentación.”
3. Si la información es técnica o de seguimiento, marca **Nota interna** para identificarla como parte del trabajo del equipo técnico. Evita incluir contraseñas o datos sensibles.
4. Haz clic en **Agregar comentario**.
5. Cuando el problema esté solucionado, cambia el estado a **Resuelto**.

> **Buena práctica:** documenta la solución antes de marcar el ticket como resuelto. Así el historial queda completo y puede servir para la Base de Conocimientos.

### 3.5 Cómo agregar equipos al inventario

1. En el menú lateral, abre **Gestión TI** y selecciona **Inventario de Equipos**.
2. Haz clic en **+ Nuevo Equipo**.
3. Completa los datos:

   | Campo | Descripción |
   | --- | --- |
   | **Código de Activo** | Código único del equipo. Obligatorio. |
   | **Tipo** | PC, Laptop, Proyector, Impresora, Escáner, Servidor, Switch, Router, Monitor, Teclado, Mouse, Parlante, Cámara Web, UPS u Otro. |
   | **Marca / Modelo / Serial** | Datos opcionales del equipo. |
   | **Equipo Padre** | Equipo al que pertenece, si aplica. |
   | **Ubicación** | Ambiente donde se encuentra. Obligatorio. |
   | **Responsable** | Usuario responsable del equipo. |
   | **Estado** | Operativo, Con Falla, En Mantenimiento o De Baja. |
   | **Fecha de Adquisición** | Fecha de compra o ingreso. |
   | **Observaciones** | Información adicional. |

4. Si el tipo es **PC** o **Laptop**, completa también:
   - Procesador.
   - RAM.
   - Disco.
   - Sistema Operativo.
   - Dirección IP.
   - Dirección MAC.

5. Haz clic en **Guardar Equipo**.
6. Para modificar un equipo existente, usa el botón **Editar** de la fila correspondiente y luego **Actualizar Equipo**.

> **Importante:** el código de activo no puede repetirse. Si el sistema indica que ya existe, revisa el inventario antes de crear un duplicado.

---

## 4. Guía para Directivos y Administradores

### 4.1 Cómo ver los reportes

Disponible para usuarios con rol **Directivo** y **Administrador**.

1. Inicia sesión.
2. Abre **Reportes** desde el menú lateral:
   - El rol **Directivo** lo encuentra en la sección **Dirección**.
   - El rol **Administrador** lo encuentra en la sección **Administración**.
3. La pantalla muestra:
   - **Tickets este mes:** tickets creados en el mes actual.
   - **Tiempo promedio de resolución:** promedio en horas entre la creación y la resolución de los tickets resueltos.
   - **Tickets resueltos:** cantidad de tickets que actualmente están en estado Resuelto.
4. Revisa los gráficos:
   - **Tickets por Estado:** gráfico de dona con la distribución por estado.
   - **Top 5 Categorías:** gráfico de barras con las cinco categorías que más tickets reciben.
5. Para actualizar la información, recarga la página.

> **Nota:** los reportes se calculan con la información registrada hasta el momento de la carga. No es un panel en tiempo real.

### 4.2 Cómo crear nuevos usuarios

Disponible únicamente para el rol **Administrador**.

1. En el menú lateral, abre **Administración** y selecciona **Usuarios**.
2. Haz clic en **+ Nuevo Usuario**.
3. Completa el formulario:

   | Campo | Descripción |
   | --- | --- |
   | **Nombre completo** | Nombre y apellidos del usuario. |
   | **Correo electrónico** | Correo institucional. Debe ser único. |
   | **Contraseña** | Mínimo 8 caracteres. |
   | **Rol** | Administrador, Técnico, Usuario o Directivo. |

4. Haz clic en **Guardar**.

Para editar un usuario:

1. Haz clic en **Editar** en la fila del usuario.
2. Modifica los datos necesarios.
3. Si no deseas cambiar la contraseña, deja el campo vacío.
4. Haz clic en **Guardar**.

> **Seguridad:** cambia las contraseñas de las cuentas de demostración antes de usar el sistema en producción. No compartas credenciales de administrador.

---

## 5. Preguntas Frecuentes (FAQ) y Soporte

### 5.1 Preguntas frecuentes

**No puedo iniciar sesión. ¿Qué hago?**
Verifica que el correo y la contraseña estén escritos correctamente. Si el problema continúa, usa la opción de recuperación de contraseña o contacta al área de TI.

**No veo la opción Bandeja de Tickets.**
La opción depende de tu perfil. Los usuarios normales pueden consultar sus tickets desde la sección de tickets o mediante la dirección `/tickets`. Si no tienes acceso, solicita apoyo al área de TI.

**No encuentro mi ticket en la bandeja.**
Revisa los filtros de estado y prioridad. Si eres usuario, la bandeja solo muestra tus propios tickets. También puedes usar el buscador por número de ticket.

**No puedo cambiar el estado de un ticket.**
El selector de estado solo aparece para el técnico asignado. Si necesitas apoyo, comunícate con el técnico responsable o con el administrador.

**¿Dónde escribo la solución del ticket?**
La solución se registra como comentario en el detalle del ticket. Si contiene información sensible, marca **Nota interna**. Luego cambia el estado a **Resuelto**.

**¿Por qué no veo borradores en la Base de Conocimientos?**
Los borradores solo son visibles para técnicos y administradores. Los usuarios y directivos ven únicamente artículos publicados.

**¿Cada cuánto se actualizan los reportes?**
Los datos se recalculan al cargar o recargar la página de reportes.

**¿Cómo cierro sesión?**
Haz clic en **Cerrar Sesión** en la parte superior derecha de la pantalla.

**¿Qué hago si el equipo no aparece en el inventario?**
Reporta la incidencia y solicita al área de TI que registre el equipo. No crees un código duplicado.

### 5.2 Soporte

Si tienes problemas para usar el sistema, comunícate con la **Oficina de Tecnologías de la Información (TI)** del Instituto TEC o con la mesa de ayuda institucional.

Para agilizar la atención, ten a mano:

- Tu nombre y correo institucional.
- El número de ticket, si ya existe.
- Una descripción del problema.
- Una captura de pantalla del error, si es posible.
- La ubicación y el equipo afectado.

---

**TEC-SGI 2026 — Sistema de Gestión de Incidencias del Instituto TEC**

Créditos: Marcos y Andy.
