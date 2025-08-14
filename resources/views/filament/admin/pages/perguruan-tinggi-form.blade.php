<x-filament-panels::page>
	{{-- <div class="max-w-4xl mx-auto py-8"> --}}
		<form wire:submit.prevent="submit" class="space-y-6">
			{{ $this->form }}
			<x-filament::button type="submit" color="primary" class="w-full mt-4">
				Simpan Perubahan
			</x-filament::button>
		</form>
	{{-- </div> --}}
</x-filament-panels::page>
