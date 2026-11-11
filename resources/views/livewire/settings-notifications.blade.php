<div class="rounded-sm border border-slate-700 bg-slate-800/95 shadow-xl shadow-black/40">
    <div class="border-b border-slate-700 px-6 py-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="font-rajdhani text-xs font-semibold uppercase tracking-[0.3em] text-[#E42B35]">Configuración</p>
                <h2 class="mt-1 font-rajdhani text-2xl font-bold uppercase tracking-wider text-white">Plantillas de notificación</h2>
                <p class="mt-1 text-sm text-slate-400">Personaliza los mensajes que reciben los usuarios en cada etapa del ticket.</p>
            </div>
            <span class="w-fit rounded-sm border border-[#284E8C] bg-[#284E8C]/20 px-3 py-1 font-rajdhani text-xs font-semibold uppercase tracking-wider text-blue-200">
                Solo administradores
            </span>
        </div>
    </div>

    <form wire:submit="save" class="space-y-6 p-6">
        @if (session('notification_message'))
            <div class="rounded-sm border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-300">
                {{ session('notification_message') }}
            </div>
        @endif

        <div>
            <label for="notification-template" class="mb-2 block font-rajdhani text-sm font-semibold uppercase tracking-wider text-slate-300">
                Plantilla
            </label>
            <select
                id="notification-template"
                wire:change="selectTemplate($event.target.value)"
                class="w-full rounded-sm border border-slate-600 bg-slate-900 px-3 py-2 text-sm text-white focus:border-[#E42B35] focus:outline-none focus:ring-2 focus:ring-[#E42B35]/30"
            >
                @foreach ($templates as $key => $template)
                    <option value="{{ $key }}" @selected($activeTemplate === $key)>{{ $template['label'] }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="notification-subject" class="mb-2 block font-rajdhani text-sm font-semibold uppercase tracking-wider text-slate-300">
                Asunto
            </label>
            <input
                id="notification-subject"
                type="text"
                wire:model="subject"
                class="w-full rounded-sm border border-slate-600 bg-slate-900 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-[#E42B35] focus:outline-none focus:ring-2 focus:ring-[#E42B35]/30"
                placeholder="Asunto del correo o notificación"
            >
            @error('subject')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="notification-body" class="mb-2 block font-rajdhani text-sm font-semibold uppercase tracking-wider text-slate-300">
                Cuerpo
            </label>
            <textarea
                id="notification-body"
                wire:model="body"
                rows="10"
                class="w-full rounded-sm border border-slate-600 bg-slate-900 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-[#E42B35] focus:outline-none focus:ring-2 focus:ring-[#E42B35]/30"
                placeholder="Contenido de la notificación"
            ></textarea>
            @error('body')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
            <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-slate-400">
                <span class="font-rajdhani uppercase tracking-wider text-slate-300">Variables disponibles:</span>
                <code class="rounded-sm bg-slate-900 px-2 py-1 text-amber-300">@{{ticket_number}}</code>
                <code class="rounded-sm bg-slate-900 px-2 py-1 text-amber-300">@{{reporter_name}}</code>
                <code class="rounded-sm bg-slate-900 px-2 py-1 text-amber-300">@{{ticket_status}}</code>
            </div>
        </div>

        <div class="flex justify-end border-t border-slate-700 pt-6">
            <button
                type="submit"
                class="btn-anim inline-flex items-center rounded-sm bg-[#E42B35] px-5 py-2.5 font-rajdhani text-sm font-bold uppercase tracking-wider text-white shadow-lg shadow-[#E42B35]/20 transition hover:bg-[#c9232c] disabled:cursor-not-allowed disabled:opacity-60"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove wire:target="save">Guardar plantilla</span>
                <span wire:loading wire:target="save">Guardando...</span>
            </button>
        </div>
    </form>
</div>
