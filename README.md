 # TextualWar

## Description
The task was to implement mini battle that consists only of the programmer part in PHP, without a visible interface.   
At the beginning of the battle, every soldier has three lives. This actually means that a soldier can be gradually wounded in battle (eg. he can be injured three times and die after that). Of course, a soldier can suddenly lose all three lives and die immediately.   
At the beginning, every soldier has five bullets in his weapon. We divide the soldiers into two groups - infantrymen and artillerymen. In both armies, the first half of the soldier is placed on infantrymen, and the other half on the artillerymen. Infantrymen and artillerymen can both attack soldiers from the opposite army. The difference is that a infantryman takes one life by attacking a soldier from an enemy army while the artilleryman takes all three lives to the soldier he attacks (he kills him).   
The soldier can attack (shoot) if he is alive and if there are more bullets in his weapon.   
The battle itself is conceived so that soldiers are attacking each other - first attacks a soldier from the first army and then a soldier from second army.   
An attacker is chosen randomly. But the attacker always attacks that soldier in the enemy army, which is "the first on the line" (the surviving soldier with the lowest index).   
Of course, the army that first destroys the enemy army wins. Therefore, defeated army is army that first loses all soldiers.   

