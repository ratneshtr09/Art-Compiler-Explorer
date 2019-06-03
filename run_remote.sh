before(){
var='classes'
sed -i '/\/\//s/class//g' $var/text.txt
usr=$(whoami)
cat "$var/text.txt" |grep '\<class\>'| sed 's/^.*class //;s/ .*$//'| sed -e "s/\r//g" > NamingClass.txt
sed -i 's/{//g' NamingClass.txt
#file1=$(grep -e 'class\|public static void main\>' "$var/text.txt"  | grep -B1 "public static void main" | grep '\<class\>' | sed "s/^.*class \+//;s/ .*$//")
#filen=${file1//{}
#filen=$(echo "$file1" | sed 's/{//g' )
filen=$(grep -e 'class\|public static void main\>' "$var/text.txt"  | grep -B1 "public static void main" | grep '\<class\>' | sed 's/{//g' | sed 's/^.*class //g' | sed 's/ .*//g' )
cp "$var/text.txt" "$var/$filen.java"
javac "$var/$filen.java" 2>error.txt
Noclass=$(sed -n \$= NamingClass.txt)
c=0
for i in $(cat NamingClass.txt);
do
	javap -c "$var/$i.class" > bytecode$c.txt
	c=$((c+1))
done
export ANDROID_ROOT=$1/$2/host/linux-x86
export ANDROID_DATA=$1

filedex="classes.dex"
flag2="--output=$filedex"
flag3="--dump-width=1000"

$1/$2/host/linux-x86/bin/dx --dex "$flag2" "$flag3" classes
filejar="test.jar"
#echo $filejar
zip  $filejar $filedex

fileodex="$filen.odex"
flag14="--oat-file=$fileodex"
fileart="$filen.art"

flag5="--compiler-backend=Optimizing"
flag7="--boot-image=$ANDROID_ROOT/framework/core.art"
flag8="--dex-file=$ANDROID_DATA/$filejar"
flag9="--oat-file=$ANDROID_DATA/$fileodex"
flag10="--base=0x4000"
flag11="--app-image-file=$ANDROID_DATA/$fileart"
flag12="--instruction-set=x86_64"

$1/$2/host/linux-x86/bin/dex2oatd "$flag5" --runtime-arg -Xnorelocate "$flag7" "$flag8" "$flag9" "$flag10" "$flag11" "$flag12"

$1/$2/host/linux-x86/bin/oatdump "$flag14" >out.txt
}

after(){
file="out.txt"

cat $file | awk '/CODE: \(code_offset/{flag=1} /OAT FILE STATS/{flag=0} flag ' > machinecode.txt
sed '/DEX CODE:/,/CODE: (code_offset/d' machinecode.txt  > machinetest.txt
sed 's/^[ \t]*//' -i machinetest.txt

sed -n '/DEX CODE:/{x;p;d;};x' $file > dexcode.txt
cat $file | awk "/DEX CODE:/{flag=1} /OatMethodOffsets/{flag=0} flag" >dexcodedetail.txt
sed "s/^[ \t]*//" -i dexcode.txt
sed "s/^[ \t]*//" -i dexcodedetail.txt

awk 'FNR==NR{a[FNR]=$0;next} /^CODE:/{$0=a[++count] ORS $0} 1' dexcode.txt machinetest.txt > machine.txt
c=0
for i in $(cat NamingClass.txt);
do 
	grep "\<$i\>" dexcode.txt >functionName$c.txt

	c=$((c+1))
done

NoOfClass=$(sed -n \$= NamingClass.txt)


c=0
for ((i=0;i<NoOfClass;i++))
do 
	ClassFun[$((c++))]=$(sed -n \$= functionName$i.txt) 	
	
done 

for ((c=0; c<NoOfClass;c++))
do
	echo ${ClassFun[$c]}
done 

c=0
for i in $(cat NamingClass.txt);
do
	sed -i "/\<$i\>/s/^/Function$((c++))/" machine.txt 	 

done

for ((i=0;i<NoOfClass;i++))
do

        for ((j=0;j<ClassFun[$i]-1;j++))
        do
                sed -n "/Function$i$j/,/Function$i$((j+1)):/{/Function$i$j:/b;/Function$i$((j+1)):/b;p}" machine.txt >machinefun$i$j.txt

        done
	sed -n "/Function$i$j/,/Function$((i+1))0:/{/Function$i$j:/b;/Function$((i+1))0:/b;p}" machine.txt >machinefun$i$j.txt

done

for ((i=0;i<NoOfClass;i++))
do

        for ((j=0;j<ClassFun[$i];j++))
        do

		sed -i 's/^/\t\t/' machinefun$i$j.txt

	done
done
		
cp dexcodedetail.txt dexcodedetail1.txt

for ((i=0;i<NoOfClass;i++))
do 
	for ((j=0;j<ClassFun[$i];j++))
	do
		sed -i -e "0,/DEX CODE:/s/DEX CODE:/DEX CODE$i$j:/" dexcodedetail1.txt
	done
done


for ((i=0;i<NoOfClass;i++))
do 
	for ((j=0;j<ClassFun[$i]-1;j++))
	do 
		sed -n "/DEX CODE$i$j:/,/DEX CODE$i$((j+1)):/{/DEX CODE$i$j:/b;/DEX CODE$i$((j+1)):/b;p}" dexcodedetail1.txt > dexcodefun$i$j.txt
                sed -i 's/^/\t\t/' dexcodefun$i$j.txt
	done
	sed -n "/DEX CODE$i$j:/,/DEX CODE$((i+1))0:/{/DEX CODE$i$j:/b;/DEX CODE$((i+1))0:/b;p}" dexcodedetail1.txt >dexcodefun$i$j.txt
	sed -i 's/^/\t\t/' dexcodefun$i$j.txt	
done

classfile="NamingClass.txt"
count=$(sed -n \$= NamingClass.txt)
for ((i=0;i<count;i++))
do
        funfile="bytecode$i.txt"
        sed -n "/Code:/{x;p;d;};x" $funfile > bytecodefun$i.txt
        sed -i "s/^[ \t]*//" bytecodefun$i.txt
        countfun=$(sed -n "\$=" bytecodefun$i.txt)
        for (( c=0;c<countfun;c++))
        do
                sed -i -e "0,/Code:/s/Code:/Code$i$c:/" $funfile
        done

        for (( c=0;c<countfun-1;c++))
        do
                sed -n "/Code$i$c:/,/Code$i$((c+1)):/{/Code$i$j:/b;/Code$i$((c+1))/b;p}" $funfile > bytecode$i$c.txt
                sed -i '$ d' bytecode$i$c.txt
                sed -i '1,1d' bytecode$i$c.txt
                sed -i "s/^[ \t]*//" bytecode$i$c.txt


		sed -i 's/^/\t\t/' bytecode$i$c.txt

        done
        sed -n "/Code$i$c:/,/Code$((i+1))0:/{/Code$i$j:/b;/Code$((i+1))0:/b;p}" $funfile > bytecode$i$c.txt
        sed -i "s/^[ \t]*//" bytecode$i$c.txt
        sed -i '1,1d' bytecode$i$c.txt
        sed -i '$ d' bytecode$i$c.txt	

	sed -i 's/^/\t\t/' bytecode$i$c.txt	
	

done
}

flag=$1
if [ $flag -eq 1 ]
then
	before $2 $3
else
	after
fi



