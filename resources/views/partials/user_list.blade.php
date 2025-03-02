@foreach ($users as $user)
    @if ($user->profile && $user->profile->avatar)
        <div class="col-md-4 mb-4">
            <a href="{{ route('profile.show', $user->id) }}" style="text-decoration: none; color: inherit;">
                <div class="preview" style="position: relative; background-image: url('{{ asset('storage/' . ($user->profile->avatar ?? 'assets/default.webp')) }}'); background-size: cover; background-position: center; height: 400px; display: flex; align-items: flex-end; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s;">
                    <div class="preview-footer" style="width: 100%; background-color: rgba(211, 211, 211, 0.9); padding: 15px; text-align: center; border-radius: 0 0 10px 10px;">
                        <h5 style="font-size: 18px; color: #333; margin-bottom: 10px;">{{ $user->name }}</h5>
                        <p id="previewBio" style="font-size: 14px; color: #333; margin: 5px 0;">
                            <i class="fa-solid fa-user"></i> Un poco sobre mí: <br>
                            <span class="preview-placeholder" style="max-width: 100%; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                {{ $user->profile->bio ?? 'Aún no has agregado una pequeña descripción de ti.' }}
                            </span>
                        </p>
                    </div>
                </div>
            </a>
        </div>
    @endif
@endforeach

{{ $users->links() }}
