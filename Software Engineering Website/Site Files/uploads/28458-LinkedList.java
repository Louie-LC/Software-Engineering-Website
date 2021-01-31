package cs304;

public class LinkedList extends List {
   
   Node root;
   
   public LinkedList() {
   
      
   
   }
   public LinkedList( int[] arr ) {
   
      root = new Node( arr[0] );
      
      for( int i = 1; i < arr.length; i++ ) {
         add( arr[i] );
      }
      
   }
   
   public void add( int val ) {
   
      if( root == null ) {
         root = new Node ( val );
         return;
      }
      
      Node temp = root;
      
      while( temp.next != null ){
         temp = temp.next;
      }
      
      temp.next = new Node( val );
   }

   public void add( int index, int val ) {
   
      if( index > size() || index < 0 ) {
         System.out.println("Invalid index");
         return;
      }
      
      if( root == null ) {
      
         root = new Node( val );
         return;
      }
      
      
   }
   
   public int size() {
   
      int count = 0;
      if( root == null ) {
         return count;
      }
      
      Node temp = root;
      count++;
      while( temp.next != null ) {
      
         temp = temp.next;
         count++;
      }
      
      return count;
   }

}

/*
   public abstract void add( int index, int val );
   
   public abstract void remove ( int index );   
   public abstract int get( int index );
   public abstract int size();
   public abstract int set( int index, int val );
   public abstract void clear();
*/