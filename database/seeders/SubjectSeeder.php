<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder {
  
    public function run(): void {
        $contents = [
            ['name' => 'Álgebra Básica', 'description' => 'Introdução à álgebra, incluindo operações com variáveis, expressões algébricas, e resolução de equações simples.'],
            ['name' => 'Geometria Euclidiana', 'description' => 'Estudo das formas, tamanhos e propriedades das figuras geométricas em um espaço plano. Inclui teoremas básicos e provas.'],
            ['name' => 'Trigonometria', 'description' => 'Análise das relações entre os ângulos e lados de triângulos. Inclui o estudo de senos, cossenos, e tangentes.'],
            ['name' => 'Cálculo Diferencial', 'description' => 'Introdução ao cálculo diferencial, incluindo conceitos de derivadas e suas aplicações em problemas de otimização.'],
            ['name' => 'Cálculo Integral', 'description' => 'Estudo das integrais e suas aplicações, como cálculo de áreas sob curvas e volumes de sólidos de revolução.'],
            ['name' => 'Teoria dos Números', 'description' => 'Ramos da matemática pura dedicado ao estudo de propriedades dos números inteiros. Inclui tópicos como divisibilidade, números primos e o teorema de Fermat.'],
            ['name' => 'Estatística', 'description' => 'Conceitos fundamentais de estatística, incluindo média, mediana, variância, desvio padrão e distribuição de probabilidade.'],
            ['name' => 'Probabilidade', 'description' => 'Estudo das medidas de incerteza. Inclui conceitos de eventos, espaço amostral, e distribuição de probabilidades.'],
            ['name' => 'Funções Matemáticas', 'description' => 'Estudo das diferentes tipos de funções, incluindo funções lineares, quadráticas, exponenciais e logarítmicas.'],
            ['name' => 'Geometria Analítica', 'description' => 'Uso de um sistema de coordenadas para representar e resolver problemas geométricos. Inclui a equação da reta e do círculo.'],
            ['name' => 'Matrizes e Determinantes', 'description' => 'Introdução ao estudo de matrizes, operações com matrizes e cálculo de determinantes. Inclui a resolução de sistemas lineares usando matrizes.'],
            ['name' => 'Números Complexos', 'description' => 'Estudo dos números complexos, suas propriedades e operações. Inclui a forma polar e a aplicação no plano complexo.'],
            ['name' => 'Sequências e Séries', 'description' => 'Introdução às sequências e séries infinitas. Inclui o estudo da convergência e divergência de séries.'],
            ['name' => 'Lógica Matemática', 'description' => 'Estudo das proposições, operadores lógicos e suas propriedades. Inclui tabelas de verdade e provas por indução.'],
            ['name' => 'Topologia', 'description' => 'Introdução ao estudo das propriedades de objetos que são preservadas sob deformações contínuas. Inclui conceitos de espaços topológicos e homeomorfismos.'],
            ['name' => 'Análise Combinatória', 'description' => 'Estudo dos métodos de contagem. Inclui permutações, combinações e o princípio da inclusão-exclusão.'],
            ['name' => 'Transformações Lineares', 'description' => 'Estudo das transformações lineares em álgebra linear, suas propriedades e aplicações.'],
            ['name' => 'Análise Real', 'description' => 'Estudo rigoroso dos números reais e funções reais. Inclui limites, continuidade e derivadas.'],
            ['name' => 'Matemática Discreta', 'description' => 'Ramo da matemática que lida com estruturas discretas. Inclui teoria dos grafos, lógica, e combinatória.'],
            ['name' => 'Geometria Não-Euclidiana', 'description' => 'Estudo das geometria em que o quinto postulado de Euclides não é válido. Inclui geometria hiperbólica e elíptica.']
        ];

        DB::table('subjects')->insert($contents);
    }
}
