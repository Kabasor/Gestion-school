<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class HistoriquePaiementEleve extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $fillable = [
        'libelle', 'slug',  'eleve_id',  'annee_scolarite_id', 'montant_paye','remise', 'dernier_payement',
        'montant_total',  'pourcentage',  'user_id', 'paiement_eleve_id', 'classe_id', 'niveau_id'
    ];

    /**
     * boot
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function ($paiementEleve) {
            $paiementEleve->user()->associate(Auth::id());
            $paiementEleve->classe()->associate(request()->classe);
            // $paiementEleve->eleve()->associate(request()->eleve);
            $paiementEleve->anneeScolarite()->associate(get_last_session_id());

        });
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'libelle'
            ]
        ];
    }



    // public function niveau()
    // {
    //     return $this->belongsTo(Niveau::class);
    // }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class)->withDefault();
    }

    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class)->withDefault();
    }

    public function paiementEleve(): BelongsTo
    {
        return $this->belongsTo(PaiementEleve::class)->withDefault();
    }

    /**
     * anneeScolarite
     *
     * @return void
     */
    public function anneeScolarite(): BelongsTo
    {
        return $this->belongsTo(Anneescolaire::class)->withDefault();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
