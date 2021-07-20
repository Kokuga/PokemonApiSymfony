<?php


    namespace App\Pokedex;


    use App\Entity\Pokemon;
    use App\Entity\Type;
    use App\Repository\PokemonRepository;
    use App\Repository\TypeRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpClient\HttpClient;
    use Symfony\Contracts\HttpClient\HttpClientInterface;

    class PokemonAPI
    {
        private HttpClientInterface $client;

        /*
         * @var PokemonRepository
         */
        private PokemonRepository $pokemonRepository;

        /*
         * @var EntityManager;
         */
        private EntityManagerInterface $em;

        private TypeRepository $typeRepository;

        public function __construct(PokemonRepository $pokemonRepository, TypeRepository $typeRepository, EntityManagerInterface $em)
        {
            $this->client = HttpClient::createForBaseUri('https://pokeapi.co/api/v2/');
            $this->pokemonRepository = $pokemonRepository;
            $this->typeRepository = $typeRepository;
            $this->em = $em;
        }

        public function getAllPokemons(int $offset = 0, int $limit = 20, array $array = []): array
        {
            $response = $this->client->request('GET', 'pokemon?offset=' . $offset . '&limit=' . $limit);
            $data = $response->toArray();
            $next = $data['next'];
            if ($next != NULL) {
                $limitExploded = explode('=', $next);
                $offsetExploded = explode('&', $limitExploded[1]);
            }

            $pokemons = $array;
            foreach ($data['results'] as $key) {
                $stringExploded = explode('/', $key['url']);
                $responsePkmn = $this->client->request('GET', 'pokemon/' . $stringExploded[6]);
                $pokemon = $responsePkmn->toArray();
                $name = $pokemon['name'];
                $height = $pokemon['height'];
                $weight = $pokemon['weight'];
                $baseExperience = $pokemon['base_experience'];
                $order = $pokemon['order'];
                $types = $pokemon['types'];
                foreach ($types as $type) {
                    $nameType = $type['type']['name'];
                    $idTypeExploded = explode('/', $type['type']['url']);
                    $pokemonType = ['name' => $nameType, 'id' => $idTypeExploded[6]];
                }
                $pokemon = ['name' => $name, 'id' => $stringExploded[6], 'height' => $height, 'weight' => $weight, 'base_experience' => $baseExperience, 'order' => $order, 'types' => ['type' => $pokemonType]];
                $pokemons[] = $this->ConvertPokeapiToPokemon($pokemon);
            }
            if ($next == null) {
                return $pokemon;
            } else {
                return $this->getAllPokemons($offsetExploded[0], $limitExploded[2], $pokemons);
            }
        }

        public function ConvertPokeapiToPokemon(array $array): Pokemon
        {
            $name = $array['name'];
            $id = $array['id'];
            $order = $array['order'];
            $height = $array['height'];
            $weight = $array['weight'];
            $baseExperience = $array['base_experience'];
            $types = $array['types'];
            $pokemon = $this->pokemonRepository->findOneBy(['pokedexOrder' => $order]);
            if ($pokemon === NULL) {
                $pokemon = new Pokemon($id);
                $pokemon->setName($name);
                $pokemon->setBaseExperience($baseExperience);
                $pokemon->setWeight($weight);
                $pokemon->setHeight($height);
                $pokemon->setPokedexOrder($order);

                foreach ($types as $type) {
                    $pokemonType = $this->typeRepository->findOneBy(['pokeapiId' => $type['id']]);
                    if ($pokemonType == NULL) {
                        $type = new Type();
                        $type->setPokeapiId($type['id']);
                        $type->setName($type['name']);

                        $this->em->persist($type);
                        $this->em->flush();
                    }

                    $pokemon->addType($pokemonType);

                    $this->em->persist($pokemon);
                    $this->em->flush();
                }
            }


            return $pokemon;
        }
    }
