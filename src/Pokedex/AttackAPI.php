<?php


    namespace App\Pokedex;



    use App\Entity\Attack;
    use App\Entity\Type;
    use App\Repository\AttackRepository;
    use App\Repository\TypeRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpClient\HttpClient;
    use Symfony\Contracts\HttpClient\HttpClientInterface;

    class AttackAPI
    {
        private HttpClientInterface $client;

        /*
         * @var AttackRepository
         */
        private AttackRepository $AttackRepository;

        /*
         * @var EntityManager;
         */
        private EntityManagerInterface $em;

        private TypeRepository $TypeRepository;

        public function __construct(AttackRepository $AttackRepository, TypeRepository $TypeRepository, EntityManagerInterface $em)
        {
            $this->client = HttpClient::createForBaseUri('https://pokeapi.co/api/v2/');
            $this->AttackRepository = $AttackRepository;
            $this->TypeRepository = $TypeRepository;
            $this->em = $em;
        }

        public function getAllAttacks(int $offset = 0, int $limit = 20, array $array = []): array
        {
            $response = $this->client->request('GET', 'move?offset=' . $offset . '&limit=' . $limit);
            $data = $response->toArray();
            $next = $data['next'];
            if ($next != NULL) {
                $limitExploded = explode('=', $next);
                $offsetExploded = explode('&', $limitExploded[1]);
            }

            $attacks = $array;
            foreach ($data['results'] as $key) {
                $stringExploded = explode('/', $key['url']);
                $responsePkmn = $this->client->request('GET', 'move/' . $stringExploded[6]);
                $attack = $responsePkmn->toArray();
                $name = $attack['name'];
                $accuracy = $attack['accuracy'];
                $pp = $attack['pp'];
                $power = $attack['power'];
                $typeName = $attack['type']['name'];
                $typeExplodedId = explode('/',$attack['type']['url']);
                $idType = $typeExplodedId[6];
                $attack = ['name' => $name, 'id' => $stringExploded[6], 'accuracy' => $accuracy, 'pp' => $pp, 'power' => $power, 'typeName' => $typeName, 'idType' => $idType];
                $attacks[] = $this->ConvertPokeapiToAttack($attack);
            }


            if ($next == null) {
                return $attack;
            } else {
                return $this->getAllAttacks($offsetExploded[0], $limitExploded[2], $attacks);
            }
        }

        public function ConvertPokeapiToAttack(array $array): Attack
        {
            $name = $array['name'];
            $id = $array['id'];
            $pp = $array['pp'];
            $accuracy = $array['accuracy'];
            if($accuracy == NULL) {
                $accuracy = 100;
            }
            $power = $array['power'];
            if($power == NULL) {
                $power = 0;
            }
            $idType = $array['idType'];
            $typeName = $array['typeName'];
            $attack = $this->AttackRepository->findOneBy(['pokeapiId' => $id]);
            $type = $this->TypeRepository->findOneBy(['pokeapiId' => $idType]);
            if($type == NULL) {
                $type = new Type();
                $type->setPokeapiId($idType);
                $type->setName($typeName);

                $this->em->persist($type);
                $this->em->flush();
            }
            if ($attack === NULL) {
                $attack = new Attack();
                $attack->setPokeapiId($id);
                $attack->setName($name);
                $attack->setAccuracy($accuracy);
                $attack->setPower($power);
                $attack->setPp($pp);
                $attack->setType($type);

                $this->em->persist($attack);
                $this->em->flush();
            }
            return $attack;
        }
    }
