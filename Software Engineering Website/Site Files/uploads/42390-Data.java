public class Data
{
 public int[][] data = { { 5, 7, 3, 4, 1},
 { 12, 8},
 { 436, 94, 99, 17}
 };
 public void methodA()
 {
 for (int i = 0; i < data.length; i++)
 {
 for (int j = 0; j <= i; j++)
 {
 System.out.print(data[i][j] + " ");
 }
 System.out.println();
 }
 }

 public void methodB()
 {
 System.out.println(data.length);
 for (int i = 0; i < data.length; i++)
 {
 System.out.print(data[i].length + " ");
 }
 System.out.println();
 }
 public void methodC()
 {
 for (int i = 0; i < data.length; i++)
 {
 for (int j = 0; j < data[i].length; j++)
 {
 if (i == j)
 System.out.print(data[i][j] + " ");
 else
 System.out.print(0 + " ");
 }
 System.out.println();
 }
 }
}