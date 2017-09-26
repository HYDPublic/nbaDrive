<?php

namespace PlayoffBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PlayoffBundle\Entity\Team;

class LoadTeam implements FixtureInterface
{

	public function load(ObjectManager $manager)
	{

		$teams = array(
				array('Toronto Raptors', 'TOR', '28', 'east', 2),
				array('Boston Celtics', 'BOS' ,'02', 'east', 5),
				array('Cleveland Cavaliers', 'CLE' ,'05', 'east', 1),
				array('Detroit Pistons', 'DET', '08', 'east', 8),
				array('Indiana Pacers', 'IND', '11', 'east', 7),
				array('Charlotte Hornets', 'CHA', '30', 'east', 6),
				array('Atlanta Hawks', 'ATL', '01', 'east', 4),
				array('Miami Heat', 'MIA', '14', 'east', 3),

				array('Oklahoma City Thunder', 'OKC', '25', 'west', 3),
				array('Portland Trail Blazers', 'POR', '22', 'west', 5),
				array('Golden State Warriors', 'GSW', '09', 'west', 1),
				array('Los Angeles Clippers', 'LAC', '12', 'west', 4),
				array('San Antonio Spurs', 'SAS', '24', 'west', 2),
				array('Houston Rockets', 'HOU', '10', 'west', 8),
				array('Memphis Grizzly', 'MEM', '29', 'west', 7),
				array('Dallas Maverick', 'DAL', '06', 'west', 6),
			);

		foreach($teams as $team){

			$t = new Team();

			$t->setName($team[0]);
			$t->setShortname($team[1]);
			$t->setAid($team[2]);
			$t->setConference($team[3]);
			$t->setRank($team[4]);

			$manager->persist($t);

		}


		$manager->flush();
	}

}
