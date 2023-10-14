<?php
namespace Eddy\Crawlers\PedalEmpire;

use Eddy\Crawlers\Shared\Robots;

/**
 * @method string forBassPedals(?int $page = null)
 * @method string forDistortion(?int $page = null)
 * @method string forOverdrive(?int $page = null)
 * @method string forFuzz(?int $page = null) 
 * @method string forFuzzWah(?int $page = null) 
 * @method string forDualOverdrive(?int $page = null) 
 * @method string forTrebleBooster(?int $page = null) 
 * @method string forBoost(?int $page = null) 
 * @method string forBuffer(?int $page = null) 
 * @method string forCompressor(?int $page = null) 
 * @method string forControllers(?int $page = null) 
 * @method string forMidiControllers(?int $page = null) 
 * @method string forLoopers(?int $page = null) 
 * @method string forTapTempo(?int $page = null) 
 * @method string forTuner(?int $page = null) 
 * @method string forPowerSupply(?int $page = null) 
 * @method string forAby(?int $page = null) 
 * @method string forExpression(?int $page = null) 
 * @method string forWah(?int $page = null) 
 * @method string forChorus(?int $page = null) 
 * @method string forPhaser(?int $page = null) 
 * @method string forTremelo(?int $page = null) 
 * @method string forPitchShift(?int $page = null) 
 * @method string forFlanger(?int $page = null) 
 * @method string forEnvelopeFilter(?int $page = null) 
 * @method string forRingModulator(?int $page = null) 
 * @method string forHarmonizer(?int $page = null) 
 * @method string forSynth(?int $page = null) 
 * @method string forMultiFx(?int $page = null) 
 * @method string forDelay(?int $page = null) 
 * @method string forReverb(?int $page = null) 
 * @method string forAnalogDelay(?int $page = null) 
 * @method string forEcho(?int $page = null) 
 * @method string forCabSimulator(?int $page = null) 
 * @method string forAcoustic(?int $page = null)
 * @method string forGuitars(?int $page = null)
 * @method string forAmps(?int $page = null)
 * 
 * @property string $bassPedals
 * @property string $distortion
 * @property string $overdrive
 * @property string $fuzz
 * @property string $fuzzWah
 * @property string $dualOverdrive
 * @property string $trebleBooster
 * @property string $boost
 * @property string $buffer
 * @property string $compressor
 * @property string $controllers
 * @property string $midiControllers
 * @property string $loopers
 * @property string $tapTempo
 * @property string $tuner
 * @property string $powerSupply
 * @property string $aby
 * @property string $expression
 * @property string $wah
 * @property string $chorus
 * @property string $phaser
 * @property string $tremelo
 * @property string $pitchShift
 * @property string $flanger
 * @property string $envelopeFilter
 * @property string $ringModulator
 * @property string $harmonizer
 * @property string $synth
 * @property string $multiFx
 * @property string $delay
 * @property string $reverb
 * @property string $analogDelay
 * @property string $echo
 * @property string $cabSimulator
 * @property string $acoustic
 * @property string $guitars
 * @property string $amps
 * 
 * @property string $robots Pedal Empire robots.txt
 * 
 */
class PedalEmpireURL
{
    public const SHORTCUTS = [
        'bassPedals' => 'bass-pedals',
        'distortion' => 'distortion',
        'overdrive' => 'overdrive',
        'drive' => 'overdrive',
        'fuzz' => 'fuzz',
        'fuzzWah' => 'fuzz-wah',
        'dualOverdrive' => 'dual-overdrive',
        'trebleBooster' => 'treble-booster',
        'treble' => 'treble-booster',
        'boost' => 'boost',
        'buffer' => 'buffer',
        'compressor' => 'compressor',
        'compressors' => 'compressor',
        'controllers' => 'controllers',
        'midiControllers' => 'midi-controllers',
        'midi' => 'midi-controllers',
        'loopers' => 'loopers',
        'tapTempo' => 'tap-tempo',
        'tempo' => 'tap-tempo',
        'tuner' => 'tuner',
        'powerSupply' => 'power-supply',
        'psu' => 'power-supply',
        'aby' => 'aby',
        'expression' => 'expression-pedal',
        'wah' => 'wah',
        'chorus' => 'chorus',
        'phaser' => 'phaser',
        'tremolo' => 'tremolo',
        'pitchShift' => 'pitch-shifting-octave',
        'pitch' => 'pitch-shifting-octave',
        'octave' => 'pitch-shifting-octave',
        'flanger' => 'flanger',
        'envelopeFilter' => 'envelope-filter',
        'envelope' => 'envelope-filter',
        'filter' => 'envelope-filter',
        'ringModulator' => 'ring-modulator',
        'ringMod' => 'ring-modulator',
        'harmonizer' => 'harmonizer',
        'synth' => 'synth',
        'multiFx' => 'multi-effects',
        'multiFX' => 'multi-effects',
        'multiEffects' => 'multi-effects',
        'delay' => 'delay-1',
        'reverb' => 'reverb',
        'analogDelay' => 'analog-delay',
        'echo' => 'echo',
        'cabSimulator' => 'cab-simulator',
        'cabSim' => 'cab-simulator',
        'acoustic' => 'acoustic',
        'guitars' => 'guitar',
        'guitar' => 'guitar',
        'amps' => 'amps',
    ];

    public const BASE = 'https://www.pedalempire.com.au';

    public function __construct()
    {}

    public function forItem(string $path)
    {
        if (!str_starts_with($path, '/')) $path = '/' . $path;
        if (str_starts_with($path, '/products')) {
            return static::BASE . $path;
        }

        return static::BASE . '/products' . $path;
    }

    public function make(string $path, ?int $page = null)
    {
        if (!str_starts_with($path, '/')) $path = '/' . $path;

        if (isset($page)) {
            $path .= '?page=' . $page;
        }

        return static::BASE . '/collections' . $path;
    }

    public function __call($name, $arguments)
    {
        if (str_starts_with($name, 'for')) {
            $name = lcfirst(substr($name, 3));
            if (isset(static::SHORTCUTS[$name])) {
                return $this->make(static::SHORTCUTS[$name], $arguments[0] ?? null);
            }
        }

        throw new \BadMethodCallException('Undefined method: ' . $name);
    }

    public function __get($name)
    {
        if ($name === 'robots') {
            return Robots::from(static::BASE);
        }
        if (isset(static::SHORTCUTS[$name])) {
            return $this->make(static::SHORTCUTS[$name]);
        }

        throw new \OutOfBoundsException('Undefined property: ' . $name);
    }
}
