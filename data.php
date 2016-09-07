<?php
/*
 * Copyright (c) 2016 Oliver Schramm
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

use Alchemy\Effect;
use Alchemy\EffectCollection;
use Alchemy\Regency;
use Alchemy\RegencyCollection;

// Effekte sind alphabetisch sortiert; true/false für positiv/negativ

EffectCollection::addEffect(new Effect('Blindheit', false));
EffectCollection::addEffect(new Effect('Energieresistenz erhöhen', true));
EffectCollection::addEffect(new Effect('Energieresistenz senken', false));
//EffectCollection::addEffect(new Effect('Energieschaden', false)); // wahrscheinlich weg
EffectCollection::addEffect(new Effect('Enthüllung', false));
EffectCollection::addEffect(new Effect('Erfrischung', true));
EffectCollection::addEffect(new Effect('Feder', true));
EffectCollection::addEffect(new Effect('Feuerresistenz erhöhen', true));
EffectCollection::addEffect(new Effect('Feuerresistenz senken', false));
EffectCollection::addEffect(new Effect('Feuerschaden', false));
EffectCollection::addEffect(new Effect('Gegengift', true));
EffectCollection::addEffect(new Effect('Geschick', true));
EffectCollection::addEffect(new Effect('Giftresistenz erhöhen', true));
EffectCollection::addEffect(new Effect('Giftresistenz senken', false));
EffectCollection::addEffect(new Effect('Giftschaden', false));
EffectCollection::addEffect(new Effect('Haarausfall', false));
EffectCollection::addEffect(new Effect('Haarwuchs', false));
EffectCollection::addEffect(new Effect('Halluzinationen', false)); //
EffectCollection::addEffect(new Effect('Heilung', true));
EffectCollection::addEffect(new Effect('Hunger', false));
EffectCollection::addEffect(new Effect('Infizieren', false));
EffectCollection::addEffect(new Effect('Intelligenz', true));
EffectCollection::addEffect(new Effect('Kälteschaden', false)); //
EffectCollection::addEffect(new Effect('Krankheitsresistenz erhöhen', true));
EffectCollection::addEffect(new Effect('Last', false));
EffectCollection::addEffect(new Effect('Lebensenergieentzug', false));
EffectCollection::addEffect(new Effect('leichte Krankheit heilen', true));
EffectCollection::addEffect(new Effect('Magieresistenz erhöhen', true));
EffectCollection::addEffect(new Effect('Magieresistenz senken', false));
EffectCollection::addEffect(new Effect('Manawiederherstellung', true));
EffectCollection::addEffect(new Effect('Manaentzug', false));
EffectCollection::addEffect(new Effect('Nachtsicht', true));
EffectCollection::addEffect(new Effect('Paralyse', false));
EffectCollection::addEffect(new Effect('Paralyse aufheben', true));
EffectCollection::addEffect(new Effect('Physische Resistenz erhöhen', true));
EffectCollection::addEffect(new Effect('Physische Resistenz senken', false));
EffectCollection::addEffect(new Effect('Sättigung', true));
EffectCollection::addEffect(new Effect('Säureschaden', false)); //
EffectCollection::addEffect(new Effect('Schlaf', false));
EffectCollection::addEffect(new Effect('Schminke', true));
EffectCollection::addEffect(new Effect('Schutz', true));
EffectCollection::addEffect(new Effect('Schwäche', false));
EffectCollection::addEffect(new Effect('Schwachsinn', false));
EffectCollection::addEffect(new Effect('schwere Krankheit heilen', true));
EffectCollection::addEffect(new Effect('Stärke', true));
EffectCollection::addEffect(new Effect('Tollpatschigkeit', false));
EffectCollection::addEffect(new Effect('Unsichtbarkeit', true));
EffectCollection::addEffect(new Effect('Zauber auflösen', true));

Regency::setEffectCollection(new EffectCollection());

// Reagenzien sind alphabetisch sortiert; Effekte müssen genauso geschrieben werden, wie oben - Reihenfolge ist hier aber egal
// Dritter Parameter des Kontruktors kann einen Default-Preis festlegen.

RegencyCollection::addRegency(new Regency('Alraune', ['Stärke', 'Intelligenz', 'Giftresistenz erhöhen', 'Blindheit']));
RegencyCollection::addRegency(new Regency('Apfel', ['Stärke', 'Heilung', 'Haarwuchs', 'Last']));
RegencyCollection::addRegency(new Regency('Bims', ['Feder', 'Nachtsicht', 'Feuerresistenz erhöhen', 'Energieresistenz erhöhen']));
RegencyCollection::addRegency(new Regency('Blut', ['Stärke', 'Intelligenz', 'Tollpatschigkeit']));
RegencyCollection::addRegency(new Regency('Blutmoos', ['Geschick', 'Krankheitsresistenz erhöhen', 'Schminke', 'Schwachsinn']));
RegencyCollection::addRegency(new Regency('Champignon', ['Intelligenz', 'Sättigung', 'Haarwuchs', 'Hunger']));
RegencyCollection::addRegency(new Regency('Coeliumerz', ['Nachtsicht', 'Energieresistenz erhöhen', 'Enthüllung' /*, 'Energieschaden'*/]));
RegencyCollection::addRegency(new Regency('Dämonenknochen', ['Physische Resistenz erhöhen', 'Manawiederherstellung', 'Magieresistenz senken', 'Physische Resistenz senken']));
RegencyCollection::addRegency(new Regency('Diamanterz', ['Physische Resistenz erhöhen', 'Unsichtbarkeit', 'Schutz', 'Lebensenergieentzug']));
RegencyCollection::addRegency(new Regency('Drachenblut', ['Magieresistenz erhöhen', 'Zauber auflösen', 'Manawiederherstellung', 'Schlaf']));
RegencyCollection::addRegency(new Regency('Efeu', ['Heilung', 'Unsichtbarkeit', 'Schlaf', 'Giftschaden']));
RegencyCollection::addRegency(new Regency('Eisenerz', ['Stärke', 'Schutz', 'Tollpatschigkeit', 'Schwachsinn']));
RegencyCollection::addRegency(new Regency('Fingerhut', ['leichte Krankheit heilen', 'Paralyse aufheben', 'Energieresistenz senken']));
RegencyCollection::addRegency(new Regency('Fledermausflügel', ['Feder', 'Krankheitsresistenz erhöhen', 'Manaentzug', 'Blindheit']));
RegencyCollection::addRegency(new Regency('Fliegenpilz', ['Manawiederherstellung', 'Manaentzug', 'Giftschaden', 'Lebensenergieentzug']));
RegencyCollection::addRegency(new Regency('Ginsengwurzel', ['Heilung', 'leichte Krankheit heilen', 'Erfrischung', 'Gegengift']));
RegencyCollection::addRegency(new Regency('Grabmoos', ['Heilung', 'Schutz', 'Halluzinationen', 'Schminke']));
RegencyCollection::addRegency(new Regency('grüne Traube', ['Stärke', 'Heilung', 'Haarwuchs', 'Paralyse']));
RegencyCollection::addRegency(new Regency('Henkerskappe', ['Zauber auflösen', 'Manawiederherstellung', 'Paralyse', 'Blindheit']));
RegencyCollection::addRegency(new Regency('Kaktus', ['Feder', 'Krankheitsresistenz erhöhen', 'Sättigung', 'Haarausfall']));
RegencyCollection::addRegency(new Regency('Knoblauch', ['Giftresistenz erhöhen', 'Krankheitsresistenz erhöhen', 'Gegengift', 'schwere Krankheit heilen']));
RegencyCollection::addRegency(new Regency('Knochen', ['Magieresistenz erhöhen', 'Schwäche', 'Kälteschaden']));
RegencyCollection::addRegency(new Regency('Krötenlaich', ['Paralyse aufheben', 'Tollpatschigkeit', 'Infizieren', 'Säureschaden']));
RegencyCollection::addRegency(new Regency('Kupfererz', ['Geschick', 'Schutz', 'Schwäche', 'Schwachsinn']));
RegencyCollection::addRegency(new Regency('Lehm', ['Physische Resistenz erhöhen', 'Sättigung', 'Blindheit', 'Säureschaden']));
RegencyCollection::addRegency(new Regency('Limone', ['leichte Krankheit heilen', 'Gegengift', 'Säureschaden', 'Feuerresistenz senken']));
RegencyCollection::addRegency(new Regency('Molchauge', ['Schwäche', 'Haarausfall', 'Halluzinationen', 'Physische Resistenz senken']));
RegencyCollection::addRegency(new Regency('Nachtschatten', ['Nachtsicht', 'schwere Krankheit heilen', 'Giftschaden', 'Lebensenergieentzug']));
RegencyCollection::addRegency(new Regency('Obsidian', ['Unsichtbarkeit', 'leichte Krankheit heilen', 'Giftschaden']));
RegencyCollection::addRegency(new Regency('Pfirsich', ['Geschick', 'leichte Krankheit heilen', 'Paralyse aufheben', 'Erfrischung']));
RegencyCollection::addRegency(new Regency('Pyrianerz', ['Feuerresistenz erhöhen', 'Last', 'Enthüllung', 'Feuerschaden']));
RegencyCollection::addRegency(new Regency('Rattenfleisch', ['Schwachsinn', 'Schlaf', 'Infizieren', 'Giftresistenz senken']));
RegencyCollection::addRegency(new Regency('roher Vogel', ['Energieresistenz erhöhen', 'Sättigung', 'Haarwuchs', 'Infizieren']));
RegencyCollection::addRegency(new Regency('Schlangenschuppe', ['Nachtsicht', 'Hunger', 'Haarausfall', 'Infizieren']));
RegencyCollection::addRegency(new Regency('schwarze Perle', ['Stärke', 'Zauber auflösen', 'Erfrischung', 'Blindheit']));
RegencyCollection::addRegency(new Regency('Schwefel', ['Intelligenz', 'Schlaf', 'Manaentzug', 'Lebensenergieentzug']));
RegencyCollection::addRegency(new Regency('Schwefelasche', ['Geschick', 'Giftschaden', 'Feuerschaden', 'Feuerresistenz senken']));
RegencyCollection::addRegency(new Regency('Seerose', ['schwere Krankheit heilen', 'Schwachsinn', 'Schlaf', 'Haarausfall']));
RegencyCollection::addRegency(new Regency('Spinnenseide', ['Feder', 'Nachtsicht', 'Giftresistenz erhöhen', 'Paralyse']));
RegencyCollection::addRegency(new Regency('Torf', ['Zauber auflösen', 'Last', 'Giftresistenz senken']));
RegencyCollection::addRegency(new Regency('totes Holz', ['Stärke', 'leichte Krankheit heilen', 'Kälteschaden', 'Halluzinationen']));
RegencyCollection::addRegency(new Regency('Traube', ['Stärke', 'Heilung', 'Feder', 'Hunger']));
RegencyCollection::addRegency(new Regency('Vulkanasche', ['Feuerresistenz erhöhen', 'Schwäche', 'Magieresistenz senken', 'Säureschaden']));
RegencyCollection::addRegency(new Regency('Weicheisen', ['Feuerresistenz erhöhen', 'Sättigung', 'Last', 'Energieresistenz senken']));
RegencyCollection::addRegency(new Regency('Wyrmherz', ['Heilung', 'Magieresistenz erhöhen', 'Unsichtbarkeit', 'Schutz']));
RegencyCollection::addRegency(new Regency('Zitrone', ['leichte Krankheit heilen', 'Gegengift', 'Feuerresistenz senken']));
RegencyCollection::addRegency(new Regency('Zwiebel', ['Krankheitsresistenz erhöhen', 'Paralyse aufheben', 'Schwäche' /*, 'Energieschaden'*/]));

// Restore prices
$prices = @file_get_contents('price_data.txt', FILE_TEXT);
RegencyCollection::setPrices($prices);
