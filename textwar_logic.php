<?php
  ini_set('max_execution_time', 300); // 300 seconds = 5 minutes

  /*
    Klasa Vojska sastoji se od zadanog broja objekata Vojnik.
    Na pocetku su svi vojnici neozlijedeni, tj. imaju 3 zivota.

    Imamo dvije vrste vojnika:
      - pjesak: napadom uzima jedan zivot slucajno odabranom vojniku iz protivnicke vojske (ranjava ga)
      - topnik: napadom uzima tri zivota sl. odabranom vojniku iz protivnicke vojske, tj. ubija ga
  */

  interface iVojska
  {
    function postaviVojnike();
  }

  class Vojska implements iVojska
  {
    // sadrzi broj vojnika u vojsci
    public $brojVojnika;

    // polje ciji elementi su vojnici
    public $vojska = array();

    function __construct( $brojVojnika ) { $this->brojVojnika = $brojVojnika; }

    // prvu polovicu vojnika postavlja na pjesake, a drugu polovicu na topnike
    // na pocetku svi imaju 3 zivota
    function postaviVojnike()
    {
      for( $i = 0; $i < ( $this->brojVojnika / 2 ); ++$i )
      {
        // prva polovica vojnika su pjesaci
        $this->vojska[ $i ] = new Pjesak;

        // postavi zivote pjesaku
        $this->vojska[ $i ]->postaviZivote();
      }

      for( $i = ( $this->brojVojnika / 2 ); $i < $this->brojVojnika; ++$i )
      {
        // druga polovica vojnika su topnici
        $this->vojska[ $i ] = new Topnik;

        // postavi zivote topniku
        $this->vojska[ $i ]->postaviZivote();
      }
    }
  };

  /*
    Klasa Vojnik za svakog vojnika pamti koliko ima zivota, te koliko metaka mu je preostalo za napad.
  */

  interface iVojnik
  {
    function napada( $targetVojska );
    function postaviZivote();
  }

  abstract class Vojnik implements iVojnik
  {
    /*
      - na pocetku svaki vojnik ima 3 zivota (elementi 0 - 2 polja $zivoti su true)
      - broj zivota = mjera toga koliko je vojnik ranjen
      - gubitak svih zivota = vojnik pogine, a njegovoj vojsci se broj vojnika smanjuje za 1
      - porazena vojska je ona koja prva dodje do 0 clanova
    */
    public $zivoti = array();

    // pamti je li vojnik ziv
    public $ziv = true;

    // na pocetku svaki vojnik ima 5 metaka
    public $brMetaka = 5;

    function postaviZivote()
    {
      for( $i = 0; $i < 3; ++$i )
        $this->zivoti[ $i ] =  true;
    }

  };

  /*
    Opcenito, ako vojnik puca (odnosno napada) smanjuje mu se broj metaka i prvi (po indeksu)
    vojnik iz protivnicke vojske je ranjen.
    Imamo dvije vrste vojnika:
      - pjesak: napadom uzima jedan zivot prvom vojniku iz protivnicke vojske (ranjava ga  - ubija ako mu
                uzima treci zivot)
      - topnik: napadom uzima tri zivota prvom vojniku iz protivnicke vojske, tj. ubija ga
  */

  class Pjesak extends Vojnik
  {
    function napada( $targetVojska )
    {
      // vojnik ne moze napasti ako je potrosio sve metke
      // takodjer, ne moze napasti ako je mrtav
      if( ( $this->brMetaka > 0 ) && ( $this->ziv ) )
      {
        // smanjuje broj metaka
        $this->brMetaka -= 1;

        // napada vojnika iz protivnicke vojske koji je prvi po indeksu
        // oduzima mu 1 zivot (ranjava ga, tj ubija ako mu uzima treci zivot)

        //trazi prvog zivog
        $i = 0;

        while( 1 )
        {
          $odabraniVojnik = $targetVojska->vojska[ $i ];

          if( $odabraniVojnik->ziv )
          {
            // oduzima mu 1 zivot
            for( $j = 0; $j < 3; ++$j )
            {
              // ako jos posjeduje i-ti zivot, oduzmi mu ga, tj. postavi na false i zavrsi
              // ako mu je to zadnji zivot, ubij ga (tj. dekrementiraj broj vojnika u njegovoj vojsci)
              if( $odabraniVojnik->zivoti[ $j ] )
              {
                $odabraniVojnik->zivoti[ $j ] = false;
                if( $j === 2 )
                {
                  $targetVojska->brojVojnika -= 1;
                  $odabraniVojnik->ziv = false;
                }

                break;
              }
            }
            break;
          }
          ++$i;
        }
      }
    }

  };

  class Topnik extends Vojnik
  {
    function napada( $targetVojska )
    {
      // vojnik ne moze napasti ako je potrosio sve metke
      // takodjer, ne moze napasti ako je mrtav
      if( ( $this->brMetaka > 0 ) && ( $this->ziv ) )
      {
        //smanji mu broj metaka
        $this->brMetaka -= 1;

        // napada vojnika iz protivnicke vojske koji je prvi po indeksu
        // oduzima mu 3 zivota, tj. ubija ga

        //trazi prvog zivog
        $i = 0;

        while( 1 )
        {
          $odabraniVojnik = $targetVojska->vojska[ $i ];

          if( $odabraniVojnik->ziv )
          {
            // oduzima mu sve preostale zivote (neovisno o tome koliko ih je izgubio do sad)
            for( $j = 0; $j < 3; ++$j )
            {
              $odabraniVojnik->zivoti[ $j ] = false;
            }
            $targetVojska->brojVojnika -= 1;
            $odabraniVojnik->ziv = false;
            break;
          }
          ++$i;
        }
      }
    }
  };

  // u n1 i n2 pamtim broj vojnika za pojedinu vojsku
  $n1 = $_GET[ 'army1' ];
  $n2 = $_GET[ 'army2' ];

  // stvorimo dvije vojske od n1 i n2 vojnika
  $vojska1 = new Vojska( $n1 );
  $vojska2 = new Vojska( $n2 );

  // postavimo vojnike u obje vojske
  $vojska1->postaviVojnike();
  $vojska2->postaviVojnike();

  // bitka
  while( ( $vojska1->brojVojnika > 0 ) && ( $vojska2->brojVojnika > 0 ) )
  {
    // slucajno odabirem vojnika koji napada (ne znam hoce li napasti pjesak ili topnik)
    // neka se vojnici naizmjenicno napadaju; prvo napada vojnik iz prve, a zatim vojnik iz druge vojske
    $indeks1 = rand( 0, ( $vojska1->brojVojnika - 1) );
    $indeks2 = rand( 0, ( $vojska2->brojVojnika - 1) );

    $napadac1 = $vojska1->vojska[ $indeks1 ];
    $napadac2 = $vojska2->vojska[ $indeks2 ];

    // neka prva vojska prva napada
    $napadac1->napada( $vojska2 );

    // zatim napada druga vojska
    $napadac2->napada( $vojska1 );

  }

?>
