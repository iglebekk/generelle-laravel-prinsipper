# Generelle Laravel-utviklingsprinsipper

Dette dokumentet inneholder generelle mønstre, pakker og beste praksis for Laravel-utvikling basert på Laravel Boost Guidelines. Disse prinsippene kan gjenbrukes på tvers av prosjekter.

## Språk

- Alle kommentarer, variabelnavn, funksjonsnavn og tekst i koden skal være på engelsk.
- UI-tekster skal lokaliseres ved hjelp av Laravel's localization system (`lang`-filer).
- Unngå hardkodet tekst i views, controllers, og andre steder i koden.
- Eksempel på lokaliserte tekster i `lang/en/resource.php`:
- Bruk alltid engelsk som grunnspråk i applikasjonen, selv om sluttbrukerne er norske. Dette sikrer konsistens i koden og gjør det enklere å legge til flere språk senere.

## Laravel-first og Spatie-first (obligatorisk)

- Default: Velg innebygd Laravel/Eloquent før custom kode.
- Default: Velg Spatie-pakker før andre tredjepartspakker når behovet dekkes.
- Default: Sjekk om Flux UI eller Filament er installert i prosjektet; hvis de er installert, skal de favoriseres for relevante UI- og admin-behov.
- Default: Installer alltid Laravel Boost MCP Tools for rask innsikt i codebase og debugging (https://laravel.com/ai/boost)

### Prioritetsrekkefølge for valg av løsning

1. Innebygd Laravel/Eloquent
2. Spatie-pakke
3. Annen etablert pakke
4. Egen implementasjon (kun når 1-3 ikke dekker behovet)

### Ikke lag custom hvis Laravel allerede har dette

- Collections: bruk Collection API (`wrap`, `map`, `filter`, `pluck`, `keyBy`, `groupBy`).
- Querying: bruk Eloquent/query builder (`when`, `whereRelation`, `withCount`, `withExists`, `exists`, `firstOrFail`, `paginate`).
- Validation/auth: Form Requests, Policies, Gates, middleware.
- Cache/queues/events/notifications/files: bruk Laravel facades og contracts.
- Routing/URLs: named routes + `route()`.
- Model behavior: bruk relationships, scopes, casts, accessors/mutators, observers.
- API output: API Resources før manuell array-bygging.

### Spatie-regel

- Sjekk alltid først om Spatie har en moden pakke for behovet.
- `spatie/laravel-permission` skal være standardvalg og installeres som default i alle prosjekter.
- Eksempler:
  - permissions/roles -> `spatie/laravel-permission`
  - media/files -> `spatie/laravel-medialibrary`
  - activity/audit logs -> `spatie/laravel-activitylog`
  - settings/data objects -> relevante Spatie-pakker
- Ved valg av annen pakke enn Spatie skal det begrunnes kort.

### Controller/Service-regel

- Controller kan inneholde effektiv, tydelig Laravel-kode.
- Flytt kun ut logikk hvis den blir gjenbrukt eller øker tydelig domeneansvar.
- Unngå abstraksjoner uten tydelig verdi.

### Forbud mot unødvendige wrappers

- Ikke lag egne helper-metoder hvis Laravel har en direkte metode.
- Ikke lag egne datastrukturer når Collection/Eloquent dekker behovet.
- Ikke bruk rå SQL hvis samme kan uttrykkes tydelig med Eloquent.

### Kvalitetskrav i PR/commit

- Ved custom løsning: skriv kort hvorfor innebygd Laravel/Spatie ikke var nok.
- All ny adferd skal ha test (happy path + validering + authorization der relevant).

## Før du implementerer

- Finnes dette i Laravel core?
- Finnes dette i Eloquent API?
- Finnes dette i Spatie?
- Er Flux UI eller Filament installert, og dekker det behovet?
- Hvis nei: kan enkel custom kode forsvares?

### Ved valg av autentisering

- Spør alltid brukeren om OAuth, SSO eller social login er viktig før du velger auth-pakke.
- Ikke default til `Laravel Breeze` før dette er avklart.
- Hvis OAuth/SSO er viktig, velg en løsning som dekker behovet naturlig og begrunn valget kort.
- Hvis OAuth/SSO ikke er viktig, er `Laravel Breeze` fortsatt anbefalt standardvalg for enkel autentisering og grunnleggende UI.

## 📦 Anbefalt Teknologi-stack

### Backend

- **Laravel siste versjon** - Moderne Laravel-struktur
- **PHP 8.3+** - Constructor property promotion, type hints
- **Laravel Breeze** - Standardvalg for autentisering og grunnleggende UI når OAuth/SSO ikke er et krav
- **spatie/laravel-permission** - Installeres som standard i alle prosjekter for roller og rettigheter
- **SQLite** (utvikling) / PostgreSQL/MySQL (produksjon)

### Lokal utvikling

- Prosjektene har som regel Laravel Valet installert lokalt, slik at nettsiden er tilgjengelig på mappenavn + `.test`.
- Hvis prosjektmappen heter `nettsiden`, er testdomenet vanligvis `nettsiden.test`.

### Frontend

- **Blade** templates med komponentbasert arkitektur
- **TailwindCSS v4** for styling
- **Alpine.js v3** for enkel interaktivitet
- **Vite** for asset bundling

### Testing & Kvalitet

- **Pest 4** - Modern PHP testing framework
- **Laravel Pint** - Code formatting (Laravel's opinionated PHP-CS-Fixer)
- Feature og unit tests med factories

### Komponentbibliotek

- **Flux UI** - Hvis installert, favoriseres Flux UI for relevante Blade-baserte UI-komponenter.
- **Filament** - Hvis installert, favoriseres Filament for relevante adminpaneler, forms, tables og ressursbaserte grensesnitt.
- **x-aui** - Foretrukket basisbibliotek for frontend-komponenter. Følg installasjon og oppsett fra https://x-aui.com/docs/0.x/installation
- **Spatie-pakker** - Foretrukket tredjeparts-leverandør (Media Library, Permissions, etc.)

## 🏗️ Arkitekturprinsipper

### Komponent-wrapper Mønster

**VIKTIG**: Bruk `x-aui` som foretrukket base for frontend-komponenter, og wrap alltid tredjepartskomponenter i egne app-spesifikke komponenter.

```
resources/views/components/
├── app/              # Dine wrapper-komponenter
│   ├── card/         # Wrapper for x-card med app-spesifikk styling
│   ├── button/       # Wrapper for x-btn
│   └── ...
├── form/             # Egne form-komponenter
├── layouts/          # Layout-komponenter
└── [domain]/         # Domene-spesifikke komponenter
```

**Fordeler**:

- Sentral kontroll over styling (f.eks. `rounded-lg` som standard)
- Enkel endring av standardverdier uten å berøre vendor-kode
- Mulighet til å bytte ut underliggende bibliotek
- Konsistens på tvers av applikasjonen

### Laravel 12 Struktur

- Middleware i `bootstrap/app.php`, ikke `app/Http/Kernel.php`
- Service providers i `bootstrap/providers.php`
- Console commands auto-registreres fra `app/Console/Commands/`
- Ingen `app/Console/Kernel.php`

## 🏢 Multi-Tenancy

### Data-tilgang via tenant (obligatorisk)

Data skal **alltid** hentes via relasjonen til tenant. Direkte spørringer på modellen er **ikke tillatt** i en multi-tenant løsning, da dette kan eksponere data på tvers av tenants.

```php
// ✅ ALLTID hent data via tenant-relasjonen
$stages = $tenant->pipelineStages()->orderBy('order')->get();
$company = $tenant->companies()->findOrFail($id);
$users = $tenant->users()->where('is_active', true)->get();

// ❌ ALDRI hent data direkte på modellen
$stages = PipelineStage::all(); // FEIL! Returnerer data fra alle tenants
$stages = PipelineStage::where('tenant_id', $tenant->id)->get(); // FEIL! Bruk relasjon i stedet
$company = Company::find($id); // FEIL! Ingen tenant-isolasjon
```

Dette sikrer at:
- Data er alltid isolert per tenant
- Det ikke er mulig å ved en feil eksponere en tenants data til en annen
- Koden er konsistent og forutsigbar

## 📝 Kodestandarder

### PHP Generelt

```php
// ✅ Bruk constructor property promotion
public function __construct(
    public GitHub $github,
    private string $apiKey,
) {}

// ✅ Alltid eksplisitte return types
public function isAccessible(User $user, ?string $path = null): bool
{
    // ...
}

// ✅ Curly braces selv for single-line
if ($condition) {
    return true;
}

// ✅ PHPDoc blocks for kompleks logikk
/**
 * @param array{name: string, email: string} $data
 * @return Collection<int, User>
 */
public function createUsers(array $data): Collection
{
    // ...
}
```

### Laravel Best Practices

```php
// ✅ Bruk Eloquent relationships, ikke raw queries
$company->pipelineStage()->first();
$tenant->pipelineStages()->orderBy('order')->get();

// ✅ Eager loading for N+1 prevention
$stages = $tenant->pipelineStages()
    ->withCount('companies')
    ->orderBy('order')
    ->get();

// ✅ Named routes
return redirect()->route('settings.stages.index');

// ✅ config() i stedet for env()
$apiKey = config('services.github.token');

// ❌ ALDRI bruk env() utenfor config-filer
$apiKey = env('GITHUB_TOKEN'); // FEIL!
```

### Controllers

```php
// ✅ Authorization i controller methods
public function index(Request $request)
{
    $this->authorize('viewAny', PipelineStage::class);
    // ...
}

// ✅ Bruk Form Requests for validering
public function store(PipelineStageRequest $request)
{
    // Validering og authorization håndteres i Request-klassen
}

// ✅ Hold controllers effektive og bruk laravel magic
public function update(Request $request, Model $model)
{
    $this->authorize('update', $model);

    $model->update($request->validated());

    return redirect()->route('resource.index')
        ->with('success', __('messages.updated'));
}
```

```php
// ✅ Unngå invokable controllers som standard
// Foretrekk standard controller-metoder: index/show/create/store/edit/update/destroy
// Unngå --invokable med mindre det er eksplisitt avtalt i oppgaven
```

### Form Requests

```php
class PipelineStageRequest extends FormRequest
{
    // ✅ Authorization i Request, ikke Controller
    public function authorize(): bool
    {
        return true; // Eller mer kompleks logikk
    }

    // ✅ Bruk array syntax
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('table')->ignore($this->route('model')),
            ],
            'color' => [
                'required',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],
        ];
    }

    // ✅ Custom error messages
    public function messages(): array
    {
        return [
            'name.required' => __('validation.name_required'),
        ];
    }
}
```

### Policies

```php
class ResourcePolicy
{
    // ✅ Enkel, tydelig authorization logic
    public function viewAny(User $user): bool
    {
        return $user->current_tenant_id !== null
            && $user->isAdminOfCurrentTenant();
    }

    public function update(User $user, Resource $resource): bool
    {
        return $user->current_tenant_id === $resource->tenant_id
            && $user->isAdminOfCurrentTenant();
    }
}
```

### Models

```php
class PipelineStage extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'color',
        'order',
        'is_active',
    ];

    // ✅ Bruk casts() method, ikke $casts property
    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    // ✅ Type-hinted relationships
    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }
}
```

## 🎨 Frontend Mønstre

### Blade Komponent Struktur

```blade
{{-- ✅ Konsistent layout pattern --}}
<x-layouts.app>
    <div class="py-6">
        <x-container class="space-y-6">
            {{-- Title --}}
            <div class="flex flex-col gap-2">
                <x-heading size="lg">{{ __('page.title') }}</x-heading>
                <x-paragraph style="muted">{{ __('page.subtitle') }}</x-paragraph>
            </div>

            {{-- Grid with sidebar --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <aside class="lg:col-span-1">
                    <x-settings.sidebar />
                </aside>

                <div class="lg:col-span-3 space-y-6">
                    {{-- Content --}}
                    <x-app.card>
                        <x-app.card.body>
                            @if($items->isEmpty())
                                <x-empty :title="..." :description="..." />
                            @else
                                {{-- Content here --}}
                            @endif
                        </x-app.card.body>
                    </x-app.card>
                </div>
            </div>
        </x-container>
    </div>
</x-layouts.app>
```

### Form Pattern

```blade
<form method="POST" action="{{ route('resource.store') }}" class="space-y-4">
    @csrf

    {{-- Input field --}}
    <div>
        <x-form.label for="name" :label="__('form.name')" />
        <x-form.input
            id="name"
            name="name"
            :value="old('name')"
            required
            class="@error('name') border-red-500 @enderror"
        />
        @error('name')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Checkbox --}}
    <div>
        <label for="is_active" class="flex items-center gap-2">
            <input
                type="checkbox"
                id="is_active"
                name="is_active"
                value="1"
                {{ old('is_active', true) ? 'checked' : '' }}
                class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
            />
            <span class="text-sm font-medium text-gray-700">{{ __('form.is_active') }}</span>
        </label>
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-3 pt-4">
        <x-btn type="submit">{{ __('form.save') }}</x-btn>
        <x-btn href="{{ route('resource.index') }}" style="ghost">{{ __('form.cancel') }}</x-btn>
    </div>
</form>
```

### Tailwind CSS Mønstre

- Bruk utility classes direkte i komponenter
- Konsistent spacing: `gap-2`, `gap-3`, `gap-4`, `space-y-4`, `space-y-6`
- Konsistent padding: `py-6`, `p-4`, `px-4`
- Responsive design: `grid-cols-1 lg:grid-cols-4`
- Consistent rounding: `rounded-lg` (8px)

## 🧪 Testing med Pest

### Test Struktur

```php
<?php

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can perform action', function () {
    // Arrange
    $tenant = Tenant::factory()->create();
    $admin = User::factory()->create(['current_tenant_id' => $tenant->id]);
    $tenant->users()->attach($admin->id, ['role' => 'admin']);

    // Act
    $response = $this->actingAs($admin)
        ->post('/resource', ['name' => 'Test']);

    // Assert
    $response->assertRedirect();
    $this->assertDatabaseHas('resources', ['name' => 'Test']);
});

test('non-admin cannot perform action', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['current_tenant_id' => $tenant->id]);
    $tenant->users()->attach($user->id, ['role' => 'user']);

    $this->actingAs($user)
        ->post('/resource', ['name' => 'Test'])
        ->assertForbidden();
});
```

### Test Best Practices

- ✅ Bruk `RefreshDatabase` trait
- ✅ Test både happy path og edge cases
- ✅ Test authorization (admin vs user)
- ✅ Test validation (required fields, unique constraints)
- ✅ Use factories for test data
- ✅ Kjør kun relevante tester: `php artisan test --filter=TestName`
- ✅ Bruk `--compact` for rask feedback

## 📚 Lokalisering

### Språkfil Struktur

```php
// lang/en/resource.php
return [
    'title' => 'Resources',
    'subtitle' => 'Manage your resources.',
    'create_new' => 'Create New Resource',
    'empty' => 'No resources yet.',

    'form' => [
        'name' => 'Name',
        'color' => 'Color',
        'save' => 'Save Changes',
        'cancel' => 'Cancel',
    ],

    'validation' => [
        'name_required' => 'Name is required.',
        'name_unique' => 'A resource with this name already exists.',
    ],

    'messages' => [
        'created' => 'Resource created successfully.',
        'updated' => 'Resource updated successfully.',
        'deleted' => 'Resource deleted successfully.',
    ],
];
```

### Bruk i Views

```blade
{{ __('resource.title') }}
{{ __('resource.form.name') }}
{{ __('resource.messages.created') }}

{{-- Med parametere --}}
{{ __('resource.count', ['count' => $items->count()]) }}
```

## 🛠️ Artisan Commands

### Opprett Nye Filer

```bash
# Controller
php artisan make:controller ResourceController --resource --no-interaction

# Model med factory og migration
php artisan make:model Resource -mf --no-interaction

# Form Request
php artisan make:request ResourceRequest --no-interaction

# Policy
php artisan make:policy ResourcePolicy --model=Resource --no-interaction

# Test
php artisan make:test ResourceTest --pest --no-interaction

# Generic PHP class
php artisan make:class Actions/DoSomethingAction --no-interaction
```

### Alltid Bruk --no-interaction

Dette sikrer at kommandoen kjører uten brukerinput, viktig for automatisering og CI/CD.

## 🎯 Workflow

### Utviklingsprosess

1. **Analyser** - Forstå eksisterende mønstre i codebase
2. **Plan** - Lag strukturert plan før implementering
3. **Implementer** - Backend først (controller, request, policy, routes)
4. **Views** - Følg etablerte UI-mønstre
5. **Lokaliser** - Legg til translation keys
6. **Test** - Skriv comprehensive feature tests
7. **Format** - Kjør `vendor/bin/pint --dirty --format agent`
8. **Verifiser** - Kjør alle tester: `php artisan test --compact`

### Database Migrations

```php
Schema::create('resources', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
    $table->string('name');
    $table->string('color');
    $table->integer('order');
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->index(['tenant_id', 'order']);
});
```

### Route Organisering

```php
// Gruppert med middleware
Route::middleware(['auth', 'tenant'])->group(function () {
    // Settings routes
    Route::prefix('settings')->group(function () {
        Route::get('/stages', [PipelineStageController::class, 'index'])
            ->name('settings.stages.index');
        Route::get('/stages/create', [PipelineStageController::class, 'create'])
            ->name('settings.stages.create');
        // etc...
    });

    // Resource routes
    Route::resource('resources', ResourceController::class);
});
```

## 📋 Sjekkliste for Nye Features

- [ ] Controller med authorization checks
- [ ] Form Request med validation og messages
- [ ] Policy med admin/user checks
- [ ] Routes med korrekt naming convention
- [ ] Views med wrapper components
- [ ] Translation keys for alle UI-tekster
- [ ] Feature tests (happy path + edge cases)
- [ ] Kjør Pint for formatering
- [ ] Kjør alle tester for å sikre ingen regresjoner
- [ ] Test manuelt i browser

## 🚀 Laravel Boost MCP Tools

Hvis du bruker Laravel Boost:

```bash
# Search dokumentasjon (VIKTIG!)
laravel-boost-mcp-search-docs --queries=["rate limiting", "validation"]

# Database queries
laravel-boost-mcp-database-query --query="SELECT * FROM users LIMIT 5"

# Tinker execution
laravel-boost-mcp-tinker --code="User::count()"

# List routes
laravel-boost-mcp-list-routes --path="settings"

# Application info
laravel-boost-mcp-application-info

# Get absolute URL
laravel-boost-mcp-get-absolute-url --path="/dashboard"
```

## 🔐 Laravel MCP Server Best Practices

Når en MCP-server eksponeres over HTTP, skal den behandles som en produksjons-APIflate med tilgang til brukerdata, interne handlinger og tredjepartsintegrasjoner. Lokale STDIO-servere kan ha implicit trust, men alle HTTP-tilgjengelige MCP-servere skal sikres eksplisitt.

### Auth-valg

- Bruk `laravel/mcp` som standardpakke for MCP-servere i Laravel.
- Bruk Laravel Passport og OAuth 2.1 som standard for produksjon og eksterne MCP-klienter.
- Registrer OAuth discovery, metadata og dynamic client registration med `Mcp::oauthRoutes()` i `routes/ai.php`.
- Beskytt HTTP-servere med Passport middleware, normalt `->middleware('auth:api')`.
- Bruk Laravel Sanctum bare for kontrollerte miljøer der både klient og server eies av samme system/team.
- Bruk custom middleware bare når prosjektet allerede har en etablert token/JWT/API-key-løsning, og middleware må alltid resolve `$request->user()`.
- Ikke bruk session-basert auth for HTTP MCP-servere. Valider bearer token per request.

```php
use App\Mcp\Servers\AppServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::oauthRoutes();

Mcp::web('/mcp/app', AppServer::class)
    ->middleware(['auth:api', 'throttle:mcp']);
```

### Authorization og tenant-scope

- Bruk `Laravel\Mcp\Request`, ikke `Illuminate\Http\Request`, i tools, prompts og resources.
- Hent alltid bruker via `$request->user()` inne i MCP primitives.
- Skjul tools, prompts og resources for brukere uten tilgang med `shouldRegister(Request $request): bool`.
- `shouldRegister` er første authorization-lag, men hvert tool må fortsatt gjøre egne Policy/Gate-sjekker før data leses eller endres.
- I multi-tenant-kode skal all lesing gå via `$request->user()->currentTenant` og tenant-relasjoner med `findOrFail()`.
- Skriveoperasjoner skal sjekkes med Policies som sammenligner `current_tenant_id` mot ressursens `tenant_id` og relevant rolle.
- Returner MCP-feilrespons ved manglende tilgang. Ikke lek detaljer om eksisterende ressurser på tvers av tenants.

```php
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class ShowProjectTool extends Tool
{
    public function shouldRegister(Request $request): bool
    {
        return $request->user()?->can('viewAny', Project::class) ?? false;
    }

    public function handle(Request $request): Response
    {
        $tenant = $request->user()->currentTenant;
        $project = $tenant->projects()->findOrFail($request->integer('project_id'));

        if ($request->user()->cannot('view', $project)) {
            return Response::error('Permission denied.');
        }

        return Response::json([
            'id' => $project->id,
            'name' => $project->name,
        ]);
    }
}
```

### Tool-design og sikkerhet

- Gi tools presise navn og smale ansvarsområder. Ett tool skal gjøre én tydelig handling.
- Valider alle tool-argumenter med Laravel MCPs argument-validering før handlinger utføres.
- Marker tools med riktige annotations: `#[IsReadOnly]`, `#[IsDestructive]`, `#[IsIdempotent]` og `#[IsOpenWorld]`.
- Bruk read-only tools som default. Destructive tools skal være eksplisitte, policy-beskyttet og audit-logget.
- Ikke forward MCP access tokens til nedstrøms API-er. Lagre tredjepartscredentials separat og bruk dem server-side.
- Bruk minimale scopes. Ikke bruk wildcard scopes for MCP.
- Legg rate limiting på alle HTTP MCP-servere, helst per bruker/token.
- Logg authorization, destructive operations og eksterne kall med nok metadata til audit, men aldri secrets eller tokens.
- Bruk queues for tunge eller langsomme handlinger, og returner tydelig status i stedet for å blokkere MCP-kallet unødvendig lenge.
- Eksponer aldri interne stack traces, SQL-feil, tokens, secrets eller tenant-identifikatorer i MCP-responser.

### Consent og token-administrasjon

- Publiser MCP authorization view med `php artisan vendor:publish --tag=mcp-views --no-interaction` når Passport brukes.
- Tilpass consent-skjermen slik at brukeren ser klientnavn, redirect URI og hvilke capabilities klienten ber om.
- Krev MFA før godkjenning når MCP-serveren gir tilgang til sensitive data eller skriveoperasjoner.
- Gi brukeren mulighet til å se og revoke MCP-tokens fra konto-/innstillingssider.
- Varsle bruker eller admin når en ny MCP-klient får tilgang til produksjonsdata.

### Testing og verifisering

- Skriv Pest-tester for hver MCP primitive: happy path, validering og authorization.
- Test at uautentiserte kall avvises for HTTP-servere.
- Test at `shouldRegister` skjuler capabilities for brukere uten tilgang.
- Test tenant-isolasjon eksplisitt: bruker i tenant A skal aldri kunne lese eller endre tenant B-data.
- Test destructive tools med både autorisert og uautorisert bruker.
- Bruk `php artisan mcp:inspector <server> --no-interaction` for manuell verifisering av auth, headers, tools, prompts og resources.

## 💡 Viktige Prinsipper

1. **Følg Laravel Conventions** - Bruk Laravels innebygde løsninger først
2. **Komponenter av Komponenter** - Wrap tredjepartsbiblioteker
3. **Test Everything** - Feature tests er påkrevd
4. **Type Hints Everywhere** - PHP 8.3+ features
5. **Lokalisering fra Start** - Ingen hardkodet tekst
6. **Keep Controllers Effective** - Large business logic i Actions/Services, but try use laravel magic and keep code in controllers.
7. **Authorization i Policies** - Ikke spredt rundt i koden
8. **Eager Loading** - Unngå N+1 queries
9. **Named Routes** - Aldri hardkodede URLs
10. **Format med Pint** - Konsistent kodestil
11. **Bruk MCP Tools** - For rask innsikt i codebase og debugging
12. **DRY Principles** - Ikke gjenta deg selv, bruk komponenter og tjenester
13. **Sikkerhet Først** - Alltid tenk på authorization og data validation
14. **Ytelse** - Optimaliser database queries og unngå unødvendige operasjoner
15. **Cache Strategisk** - Bruk caching for å forbedre ytelsen der det gir mening
16. **Multi-Tenant Isolasjon** - Hent alltid data via tenant-relasjonen, aldri direkte på modellen

## 🔗 Nyttige Ressurser

- Laravel Documentation: https://laravel.com/docs
- Pest Documentation: https://pestphp.com
- Tailwind CSS Documentation: https://tailwindcss.com
- Laravel Best Practices: https://github.com/alexeymezenin/laravel-best-practices
- Spatie Packages: https://spatie.be/open-source
